<?php
// ============================================================
// API: Orders - PLACE, VIEW, UPDATE STATUS
// ============================================================
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getOrder($db, (int)$_GET['id']);
        } else {
            getAllOrders($db);
        }
        break;
    case 'POST':
        placeOrder($db);
        break;
    case 'PUT':
        updateOrderStatus($db);
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

function placeOrder($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) jsonResponse(['success' => false, 'message' => 'Invalid request body'], 400);

    $name    = sanitize($input['customer_name'] ?? '');
    $phone   = sanitize($input['customer_phone'] ?? '');
    $email   = sanitize($input['customer_email'] ?? '');
    $type    = sanitize($input['order_type'] ?? 'dine-in');
    $address = sanitize($input['delivery_address'] ?? '');
    $items   = $input['items'] ?? [];
    $notes   = sanitize($input['notes'] ?? '');
    $payment = sanitize($input['payment_method'] ?? 'cash');

    if (empty($name)) jsonResponse(['success' => false, 'message' => 'Customer name is required'], 400);
    if (empty($phone)) jsonResponse(['success' => false, 'message' => 'Phone number is required'], 400);
    if (empty($items) || !is_array($items)) jsonResponse(['success' => false, 'message' => 'No items in cart'], 400);
    if ($type === 'delivery' && empty($address)) jsonResponse(['success' => false, 'message' => 'Delivery address is required'], 400);

    // Validate & enrich items from DB
    $enriched = [];
    $subtotal = 0;
    foreach ($items as $item) {
        $item_id  = (int)($item['id'] ?? 0);
        $qty      = max(1, (int)($item['qty'] ?? 1));
        if (!$item_id) continue;

        $stmt = $db->prepare("SELECT id, name, price, image_url FROM menu WHERE id = ? AND is_available = 1");
        $stmt->execute([$item_id]);
        $dbItem = $stmt->fetch();
        if (!$dbItem) continue;

        $enriched[] = [
            'id'    => $dbItem['id'],
            'name'  => $dbItem['name'],
            'price' => (float)$dbItem['price'],
            'qty'   => $qty,
            'image' => $dbItem['image_url']
        ];
        $subtotal += $dbItem['price'] * $qty;
    }

    if (empty($enriched)) jsonResponse(['success' => false, 'message' => 'No valid items found'], 400);

    $tax          = round($subtotal * TAX_RATE, 2);
    $delivery_fee = ($type === 'delivery') ? DELIVERY_FEE : 0;
    $total        = $subtotal + $tax + $delivery_fee;

    try {
        $stmt = $db->prepare("INSERT INTO orders (customer_name, customer_phone, customer_email, order_type, delivery_address, items, subtotal, tax, delivery_fee, total_amount, payment_method, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$name, $phone, $email, $type, $address, json_encode($enriched, JSON_UNESCAPED_UNICODE), $subtotal, $tax, $delivery_fee, $total, $payment, $notes]);
        $orderId = $db->lastInsertId();
        jsonResponse([
            'success'  => true,
            'message'  => 'Order placed successfully! Your order ID is #' . str_pad($orderId, 4, '0', STR_PAD_LEFT),
            'order_id' => $orderId,
            'total'    => $total
        ]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to place order'], 500);
    }
}

function getAllOrders($db) {
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    try {
        $status = $_GET['status'] ?? '';
        $sql    = "SELECT * FROM orders";
        $params = [];
        if ($status) {
            $sql   .= " WHERE status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();
        foreach ($orders as &$o) {
            $o['items'] = json_decode($o['items'], true);
        }
        jsonResponse(['success' => true, 'data' => $orders, 'count' => count($orders)]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch orders'], 500);
    }
}

function getOrder($db, $id) {
    try {
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if ($order) {
            $order['items'] = json_decode($order['items'], true);
            jsonResponse(['success' => true, 'data' => $order]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Order not found'], 404);
        }
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch order'], 500);
    }
}

function updateOrderStatus($db) {
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $input  = json_decode(file_get_contents('php://input'), true);
    $id     = (int)($input['id'] ?? 0);
    $status = sanitize($input['status'] ?? '');
    $allowed = ['pending','confirmed','preparing','ready','delivered','cancelled'];

    if (!$id) jsonResponse(['success' => false, 'message' => 'Order ID required'], 400);
    if (!in_array($status, $allowed)) jsonResponse(['success' => false, 'message' => 'Invalid status'], 400);

    try {
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        jsonResponse(['success' => true, 'message' => 'Order status updated to ' . $status]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to update status'], 500);
    }
}
?>
