<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? '';
$id_code = $data['id_code'] ?? '';
$new_password = $data['new_password'] ?? '';

if (!$username || !$id_code || !$new_password) {
    echo json_encode(['success' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit;
}

try {
    // Verify identity: Username must match Student Code or Staff/Teacher Code
    // First, check students
    $stmt = $pdo->prepare("
        SELECT u.id, u.role 
        FROM users u 
        JOIN students s ON u.id = s.user_id 
        WHERE u.username = ? AND s.student_code = ?
    ");
    $stmt->execute([$username, $id_code]);
    $user = $stmt->fetch();

    // If not found in students, check teachers
    if (!$user) {
        $stmt = $pdo->prepare("
            SELECT u.id, u.role 
            FROM users u 
            JOIN teachers t ON u.id = t.user_id 
            WHERE u.username = ? AND t.staff_code = ?
        ");
        $stmt->execute([$username, $id_code]);
        $user = $stmt->fetch();
    }

    if ($user) {
        // Correct identity! Update password in users table
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashed_password, $user['id']]);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'ข้อมูลยืนยันตัวตนไม่ถูกต้อง (ชื่อผู้ใช้ไม่ตรงกับรหัสประจำตัว)']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
