<?php
header('Content-Type: application/json');
require_once 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Only POST requests are allowed']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
$email = trim($data['email'] ?? '');
$role = $data['role'] ?? 'student'; // 'student' or 'teacher'

$firstName = trim($data['first_name'] ?? '');
$lastName = trim($data['last_name'] ?? '');
$code = trim($data['code'] ?? ''); 
$majorOrDept = trim($data['major_or_dept'] ?? '');
$gender = trim($data['gender'] ?? 'ชาย');
$year = intval($data['year'] ?? 1);

if (empty($username) || empty($password) || empty($email) || empty($firstName) || empty($lastName) || empty($code)) {
    echo json_encode(['success' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
    exit;
}

// STRICT VALIDATION
if ($role === 'student' && strlen($code) !== 11) {
    echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ถูกต้อง: รหัสนิสิตต้องมี 11 หลัก']);
    exit;
}

if (strlen($password) !== 13) {
    echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ถูกต้อง: รหัสผ่านต้องเป็นเลขบัตรประชาชน 13 หลัก']);
    exit;
}

if (!in_array($role, ['student', 'teacher'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid role']);
    exit;
}

try {
    // 1. Check for duplicates (Username, Email, or Code)
    $check_stmt = $pdo->prepare("
        SELECT 'username' as type FROM users WHERE username = ?
        UNION ALL
        SELECT 'email' as type FROM users WHERE email = ?
        UNION ALL
        SELECT 'code' as type FROM students WHERE student_code = ?
        UNION ALL
        SELECT 'code' as type FROM teachers WHERE staff_code = ?
    ");
    $check_stmt->execute([$username, $email, $code, $code]);
    $duplicate = $check_stmt->fetch();

    if ($duplicate) {
        $msg = 'พบข้อมูลซ้ำในระบบ';
        if ($duplicate['type'] === 'username') $msg = 'ชื่อผู้ใช้งานนี้ถูกใช้ไปแล้ว';
        elseif ($duplicate['type'] === 'email') $msg = 'อีเมลนี้ถูกใช้ไปแล้ว';
        elseif ($duplicate['type'] === 'code') $msg = 'รหัสนิสิต/บุคลากรนี้ถูกใช้ไปแล้ว';
        
        echo json_encode(['success' => false, 'error' => $msg]);
        exit;
    }

    $pdo->beginTransaction();

    // 2. Insert into users table
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->execute([$username, $hashed_password, $role, $email]);
    $userId = $pdo->lastInsertId();

    // 3. Insert into profile table
    if ($role === 'student') {
        $stmt_profile = $pdo->prepare("INSERT INTO students (user_id, student_code, first_name, last_name, major, gender, year) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_profile->execute([$userId, $code, $firstName, $lastName, $majorOrDept, $gender, $year]);
    } else {
        $stmt_profile = $pdo->prepare("INSERT INTO teachers (user_id, staff_code, first_name, last_name, department) VALUES (?, ?, ?, ?, ?)");
        $stmt_profile->execute([$userId, $code, $firstName, $lastName, $majorOrDept]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'ลงทะเบียนสำเร็จ! สามารถเข้าสู่ระบบได้ทันที']);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดทางเทคนิค: ' . $e->getMessage()]);
}
?>
