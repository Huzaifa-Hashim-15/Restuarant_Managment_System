<?php
// ============================================================
// API: Reservations - CREATE, VIEW
// ============================================================
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getAllReservations($db);
        break;
    case 'POST':
        createReservation($db);
        break;
    case 'PUT':
        updateReservationStatus($db);
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

function createReservation($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    $name     = sanitize($input['name'] ?? '');
    $email    = sanitize($input['email'] ?? '');
    $phone    = sanitize($input['phone'] ?? '');
    $date     = sanitize($input['date'] ?? '');
    $time     = sanitize($input['time'] ?? '');
    $guests   = (int)($input['guests'] ?? 2);
    $occasion = sanitize($input['occasion'] ?? '');
    $requests = sanitize($input['special_requests'] ?? '');

    // Validation
    if (empty($name))  jsonResponse(['success' => false, 'message' => 'Name is required'], 400);
    if (!validateEmail($email)) jsonResponse(['success' => false, 'message' => 'Valid email is required'], 400);
    if (empty($phone)) jsonResponse(['success' => false, 'message' => 'Phone number is required'], 400);
    if (empty($date))  jsonResponse(['success' => false, 'message' => 'Date is required'], 400);
    if (empty($time))  jsonResponse(['success' => false, 'message' => 'Time is required'], 400);
    if ($guests < 1 || $guests > 20) jsonResponse(['success' => false, 'message' => 'Guests must be between 1 and 20'], 400);

    // Date must be today or future
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        jsonResponse(['success' => false, 'message' => 'Reservation date must be today or in the future'], 400);
    }

    try {
        $stmt = $db->prepare("INSERT INTO reservations (name, email, phone, date, time, guests, occasion, special_requests) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$name, $email, $phone, $date, $time, $guests, $occasion, $requests]);
        jsonResponse([
            'success' => true,
            'message' => 'Reservation submitted! We will confirm via email/phone shortly.',
            'id'      => $db->lastInsertId()
        ]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to create reservation'], 500);
    }
}

function getAllReservations($db) {
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    try {
        $stmt = $db->prepare("SELECT * FROM reservations ORDER BY date ASC, time ASC");
        $stmt->execute();
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch reservations'], 500);
    }
}

function updateReservationStatus($db) {
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    $input  = json_decode(file_get_contents('php://input'), true);
    $id     = (int)($input['id'] ?? 0);
    $status = sanitize($input['status'] ?? '');
    $allowed = ['pending','confirmed','cancelled','completed'];

    if (!$id) jsonResponse(['success' => false, 'message' => 'ID required'], 400);
    if (!in_array($status, $allowed)) jsonResponse(['success' => false, 'message' => 'Invalid status'], 400);

    try {
        $stmt = $db->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        jsonResponse(['success' => true, 'message' => 'Reservation status updated']);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to update reservation'], 500);
    }
}
?>
