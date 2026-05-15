<?php
// ============================================================
// API: Contact - STORE messages
// ============================================================
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        storeContact($db);
        break;
    case 'GET':
        getAllContacts($db);
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

function storeContact($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;

    $name    = sanitize($input['name'] ?? '');
    $email   = sanitize($input['email'] ?? '');
    $phone   = sanitize($input['phone'] ?? '');
    $subject = sanitize($input['subject'] ?? '');
    $message = sanitize($input['message'] ?? '');

    if (empty($name))    jsonResponse(['success' => false, 'message' => 'Name is required'], 400);
    if (!validateEmail($email)) jsonResponse(['success' => false, 'message' => 'Valid email is required'], 400);
    if (empty($message)) jsonResponse(['success' => false, 'message' => 'Message is required'], 400);
    if (strlen($message) < 10) jsonResponse(['success' => false, 'message' => 'Message is too short'], 400);

    try {
        $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        jsonResponse(['success' => true, 'message' => 'Message sent! We will get back to you within 24 hours.']);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to send message'], 500);
    }
}

function getAllContacts($db) {
    if (!isAdminLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
    }
    try {
        $stmt = $db->prepare("SELECT * FROM contacts ORDER BY created_at DESC");
        $stmt->execute();
        jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Failed to fetch messages'], 500);
    }
}
?>
