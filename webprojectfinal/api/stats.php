<?php
// ============================================================
// API: Dashboard Statistics
// ============================================================
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isAdminLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$db = getDB();

try {
    // Total orders
    $stmt = $db->query("SELECT COUNT(*) AS total, SUM(total_amount) AS revenue FROM orders WHERE status != 'cancelled'");
    $orderStats = $stmt->fetch();

    // Today's orders
    $stmt = $db->query("SELECT COUNT(*) AS today FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'");
    $todayOrders = $stmt->fetch();

    // Orders by status
    $stmt = $db->query("SELECT status, COUNT(*) AS count FROM orders GROUP BY status");
    $statusBreakdown = $stmt->fetchAll();

    // Total customers (unique phones)
    $stmt = $db->query("SELECT COUNT(DISTINCT customer_phone) AS total FROM orders");
    $customers = $stmt->fetch();

    // Total reservations
    $stmt = $db->query("SELECT COUNT(*) AS total FROM reservations");
    $reservations = $stmt->fetch();

    // Pending reservations
    $stmt = $db->query("SELECT COUNT(*) AS pending FROM reservations WHERE status = 'pending'");
    $pendingRes = $stmt->fetch();

    // Unread messages
    $stmt = $db->query("SELECT COUNT(*) AS unread FROM contacts WHERE is_read = 0");
    $unreadMsgs = $stmt->fetch();

    // Total menu items
    $stmt = $db->query("SELECT COUNT(*) AS total FROM menu WHERE is_available = 1");
    $menuItems = $stmt->fetch();

    // Recent orders (last 5)
    $stmt = $db->query("SELECT id, customer_name, total_amount, status, created_at FROM orders ORDER BY created_at DESC LIMIT 5");
    $recentOrders = $stmt->fetchAll();

    // Revenue last 7 days
    $stmt = $db->query("SELECT DATE(created_at) AS date, SUM(total_amount) AS revenue, COUNT(*) AS orders FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND status != 'cancelled' GROUP BY DATE(created_at) ORDER BY date ASC");
    $weeklyRevenue = $stmt->fetchAll();

    // Top selling items
    $allOrders = $db->query("SELECT items FROM orders WHERE status != 'cancelled'")->fetchAll();
    $itemCount  = [];
    foreach ($allOrders as $o) {
        $items = json_decode($o['items'], true);
        if (is_array($items)) {
            foreach ($items as $item) {
                $n = $item['name'] ?? 'Unknown';
                $itemCount[$n] = ($itemCount[$n] ?? 0) + ($item['qty'] ?? 1);
            }
        }
    }
    arsort($itemCount);
    $topItems = array_slice($itemCount, 0, 5, true);

    jsonResponse([
        'success' => true,
        'data'    => [
            'total_orders'         => (int)$orderStats['total'],
            'total_revenue'        => (float)$orderStats['revenue'],
            'today_orders'         => (int)$todayOrders['today'],
            'total_customers'      => (int)$customers['total'],
            'total_reservations'   => (int)$reservations['total'],
            'pending_reservations' => (int)$pendingRes['pending'],
            'unread_messages'      => (int)$unreadMsgs['unread'],
            'menu_items'           => (int)$menuItems['total'],
            'status_breakdown'     => $statusBreakdown,
            'recent_orders'        => $recentOrders,
            'weekly_revenue'       => $weeklyRevenue,
            'top_items'            => $topItems
        ]
    ]);
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to load stats'], 500);
}
?>
