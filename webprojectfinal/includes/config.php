<?php
// ============================================================
// DASTARKHAN RESTAURANT - Database Configuration
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dastarkhan_db');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'Dastarkhan Restaurant');
define('SITE_URL', 'http://localhost/restaurant');
define('UPLOAD_PATH', __DIR__ . '/../uploads/menu/');
define('UPLOAD_URL', SITE_URL . '/uploads/menu/');

// Tax Rate (9%)
define('TAX_RATE', 0.09);
define('DELIVERY_FEE', 100);

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

function getDB() {
    return Database::getInstance()->getConnection();
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isAdminLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}
?>
