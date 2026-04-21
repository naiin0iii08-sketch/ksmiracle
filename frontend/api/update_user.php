<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only admins can update users
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$userId = $data['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['error' => 'Missing user ID']);
    exit;
}

$username = $data['username'] ?? '';
$role = $data['role'] ?? '';
$email = $data['email'] ?? '';
$firstName = $data['first_name'] ?? '';
$lastName = $data['last_name'] ?? '';
$code = $data['code'] ?? ''; 
$majorOrDept = $data['major_or_dept'] ?? '';
$year = $data['year'] ?? null;
$status = $data['status'] ?? 'active';

try {
    $pdo->beginTransaction();

    // 1. Update users table
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, status = ? WHERE id = ?");
    $stmt->execute([$username, $email, $status, $userId]);

    // 2. Update profile table based on role
    if ($role === 'student') {
        $stmt_profile = $pdo->prepare("UPDATE students SET student_code = ?, first_name = ?, last_name = ?, major = ?, year = ? WHERE user_id = ?");
        $stmt_profile->execute([$code, $firstName, $lastName, $majorOrDept, $year, $userId]);
    } elseif ($role === 'teacher') {
        $stmt_profile = $pdo->prepare("UPDATE teachers SET staff_code = ?, first_name = ?, last_name = ?, department = ? WHERE user_id = ?");
        $stmt_profile->execute([$code, $firstName, $lastName, $majorOrDept, $userId]);
    } elseif ($role === 'admin') {
        $stmt_profile = $pdo->prepare("UPDATE admins SET admin_code = ?, first_name = ?, last_name = ? WHERE user_id = ?");
        $stmt_profile->execute([$code, $firstName, $lastName, $userId]);
    }

    $pdo->commit();
    echo json_encode(['success' => 'User updated successfully']);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
