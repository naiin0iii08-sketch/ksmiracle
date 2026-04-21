<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only students can access this
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // 1. Get the numeric student_id first
    $stmt = $pdo->prepare("SELECT id FROM students WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $student = $stmt->fetch();

    if (!$student) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูลนิสิต']);
        exit;
    }

    $student_id = $student['id'];

    // 2. Fetch requests for this student
    $stmt = $pdo->prepare("SELECT * FROM internship_requests WHERE student_id = ? ORDER BY created_at DESC");
    $stmt->execute([$student_id]);
    $requests = $stmt->fetchAll();

    echo json_encode(['success' => true, 'requests' => $requests]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
