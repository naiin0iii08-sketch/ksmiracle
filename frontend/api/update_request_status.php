<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Admins OR Teachers can update status
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$is_teacher = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teacher';

if (!$is_admin && !$is_teacher) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$request_id = $data['request_id'] ?? null;
$status = $data['status'] ?? null;

if (!$request_id || !$status) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

// Security: Teachers can ONLY set advisor_approved
if ($is_teacher && !$is_admin) {
    if ($status !== 'advisor_approved') {
        echo json_encode(['success' => false, 'error' => 'อาจารย์มีสิทธิ์อนุมัติเบื้องต้นเท่านั้น']);
        exit;
    }
}

// Valid statuses
$valid_statuses = ['pending', 'advisor_approved', 'letter_issued', 'completed', 'cancelled'];

if (in_array($status, $valid_statuses)) {
    try {
        $stmt = $pdo->prepare("UPDATE internship_requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $request_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'สถานะไม่ถูกต้อง']);
}
?>
