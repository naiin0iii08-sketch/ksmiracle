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
    // Verify identity: Username must match Student Code or Staff Code
    // First, check students table
    $stmt = $pdo->prepare("SELECT * FROM students WHERE username = ? AND student_code = ?");
    $stmt->execute([$username, $id_code]);
    $user = $stmt->fetch();

    // If not found in students, check staff
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ? AND staff_code = ?");
        $stmt->execute([$username, $id_code]);
        $user = $stmt->fetch();
        $is_staff = true;
    } else {
        $is_staff = false;
    }

    if ($user) {
        // Correct identity! Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        if ($is_staff) {
            $update = $pdo->prepare("UPDATE staff SET password = ? WHERE id = ?");
        } else {
            $update = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
        }
        
        $update->execute([$hashed_password, $user['id']]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'ข้อมูลยืนยันตัวตนไม่ถูกต้อง (ชื่อผู้ใช้ไม่ตรงกับรหัสประจำตัว)']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
