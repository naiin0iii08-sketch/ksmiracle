<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only admins can add users
requireAdmin();

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$username = $data['username'] ?? '';
$password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
$role = $data['role'] ?? ''; // 'student' or 'teacher' or 'admin'
$email = $data['email'] ?? '';

// Specific profile data
$firstName = $data['first_name'] ?? '';
$lastName = $data['last_name'] ?? '';
$code = $data['code'] ?? ''; // student_code or staff_code
$majorOrDept = $data['major_or_dept'] ?? '';
$year = $data['year'] ?? null;
$status = $data['status'] ?? 'active';

try {
    $pdo->beginTransaction();

    // 1. Insert into users table
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $email, $status]);
    $userId = $pdo->lastInsertId();

    // 2. Insert into specific profile table based on role
    if ($role === 'student') {
        $stmt_profile = $pdo->prepare("INSERT INTO students (user_id, student_code, first_name, last_name, major, year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_profile->execute([$userId, $code, $firstName, $lastName, $majorOrDept, $year]);
    } elseif ($role === 'teacher') {
        $stmt_profile = $pdo->prepare("INSERT INTO teachers (user_id, staff_code, first_name, last_name, department) VALUES (?, ?, ?, ?, ?)");
        $stmt_profile->execute([$userId, $code, $firstName, $lastName, $majorOrDept]);
    } elseif ($role === 'admin') {
        $stmt_profile = $pdo->prepare("INSERT INTO admins (user_id, admin_code, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt_profile->execute([$userId, $code, $firstName, $lastName]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'User created successfully', 'user_id' => $userId]);

} catch (PDOException $e) {
    $pdo->rollBack();
    // Catch duplicate entry error (error code 23000)
    if ($e->getCode() == 23000) {
        $errorMsg = $e->getMessage();
        if (strpos($errorMsg, 'username') !== false) {
            echo json_encode(['error' => 'Username นี้มีอยู่ในระบบแล้ว กรุณาใช้ชื่ออื่น']);
        } elseif (strpos($errorMsg, 'email') !== false) {
            echo json_encode(['error' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณาใช้อีเมลอื่น']);
        } elseif (strpos($errorMsg, 'code') !== false) {
            echo json_encode(['error' => 'รหัสนี้ (รหัสนิสิต/บุคลากร/ผู้ดูแล) ถูกใช้งานแล้ว']);
        } else {
            echo json_encode(['error' => 'ข้อมูลซ้ำซ้อนในระบบ']);
        }
    } else {
        echo json_encode(['error' => 'ไม่สามารถเพิ่มผู้ใช้ได้: ' . $e->getMessage()]);
    }
}
?>
