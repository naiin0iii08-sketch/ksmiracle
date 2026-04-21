<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Admins OR Teachers can see requests
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'teacher')) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    // Join with students table to get student names and details
    $sql = "SELECT r.*, s.first_name, s.last_name, s.major, s.student_code, s.year 
            FROM internship_requests r 
            JOIN students s ON r.student_id = s.student_id 
            ORDER BY r.created_at DESC";
    $stmt = $pdo->query($sql);
    $requests = $stmt->fetchAll();

    echo json_encode(['success' => true, 'requests' => $requests]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
