<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Fetch only the latest internship request for each student to avoid duplicates
    // Using a subquery to find the maximum ID (latest) per student_id
    $sql = "SELECT s.first_name, s.last_name, s.year, s.major, r.status, r.company_name
            FROM students s
            JOIN internship_requests r ON s.student_id = r.student_id
            WHERE r.id IN (
                SELECT MAX(id) 
                FROM internship_requests 
                WHERE status != 'cancelled'
                GROUP BY student_id
            )
            ORDER BY r.created_at DESC";
            
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
