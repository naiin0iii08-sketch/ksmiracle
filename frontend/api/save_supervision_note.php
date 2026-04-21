<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only teachers and admins
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$is_teacher = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teacher';

if (!$is_admin && !$is_teacher) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$request_id = $data['request_id'] ?? null;
$note = $data['note'] ?? '';

if (!$request_id) {
    echo json_encode(['success' => false, 'error' => 'Missing ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE internship_requests SET supervision_note = ? WHERE id = ?");
    $stmt->execute([$note, $request_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
