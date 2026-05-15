<?php
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $db = getDB();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $db->query("SELECT id, full_name as name, email, phone, designation as role, 
                                   salary, status, hire_date 
                            FROM employees ORDER BY id DESC");
        $employees = $stmt->fetchAll();
        foreach ($employees as &$emp) {
            $emp['salary'] = floatval($emp['salary']);
        }
        jsonResponse(['success' => true, 'data' => $employees]);
    }
    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['full_name']) || empty($input['email']) || 
            empty($input['designation']) || empty($input['salary'])) {
            jsonResponse(['success' => false, 'message' => 'Missing required fields'], 400);
        }
        if (!validateEmail($input['email'])) {
            jsonResponse(['success' => false, 'message' => 'Invalid email'], 400);
        }
        $name = sanitize($input['full_name']);
        $email = sanitize($input['email']);
        $phone = sanitize($input['phone'] ?? '');
        $designation = sanitize($input['designation']);
        $salary = floatval($input['salary']);
        $password = password_hash('employee123', PASSWORD_DEFAULT);

        $check = $db->prepare("SELECT id FROM employees WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Email already exists'], 400);
        }

        $stmt = $db->prepare("INSERT INTO employees (full_name, email, phone, designation, salary, password, status) 
                              VALUES (?, ?, ?, ?, ?, ?, 'Active')");
        $stmt->execute([$name, $email, $phone, $designation, $salary, $password]);
        jsonResponse(['success' => true, 'message' => 'Employee added successfully', 'id' => $db->lastInsertId()]);
    }
    elseif ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['id'])) {
            jsonResponse(['success' => false, 'message' => 'Missing employee ID'], 400);
        }
        $id = intval($input['id']);

        // Only status change (fire/rehire)
        if (isset($input['status']) && count($input) == 2) {
            $status = sanitize($input['status']);
            $stmt = $db->prepare("UPDATE employees SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            jsonResponse(['success' => true, 'message' => 'Status updated successfully']);
            exit;
        }

        // Full edit
        $allowedFields = ['full_name', 'email', 'phone', 'designation', 'salary'];
        $updates = [];
        $values = [];

        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                if ($field === 'salary') {
                    $updates[] = "$field = ?";
                    $values[] = floatval($input[$field]);
                } elseif ($field === 'email') {
                    if (!validateEmail($input[$field])) {
                        jsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
                    }
                    $check = $db->prepare("SELECT id FROM employees WHERE email = ? AND id != ?");
                    $check->execute([sanitize($input[$field]), $id]);
                    if ($check->fetch()) {
                        jsonResponse(['success' => false, 'message' => 'Email already in use by another employee'], 400);
                    }
                    $updates[] = "$field = ?";
                    $values[] = sanitize($input[$field]);
                } else {
                    $updates[] = "$field = ?";
                    $values[] = sanitize($input[$field]);
                }
            }
        }

        if (empty($updates)) {
            jsonResponse(['success' => false, 'message' => 'No fields to update'], 400);
        }

        $values[] = $id;
        $sql = "UPDATE employees SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute($values);

        jsonResponse(['success' => true, 'message' => 'Employee updated successfully']);
    }
    else {
        jsonResponse(['success' => false, 'message' => 'Invalid method'], 405);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}