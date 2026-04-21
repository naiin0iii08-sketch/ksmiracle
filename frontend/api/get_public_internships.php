<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Fetch only the latest internship request for each student to avoid duplicates
    // Using a subquery to find the maximum ID (latest) per student_id
    // Fetch students and their latest internship status (if any)
    $sql = "SELECT s.first_name, s.last_name, s.year, s.major, r.status, r.company_name
            FROM students s
            LEFT JOIN (
                SELECT student_id, status, company_name, 
                       ROW_NUMBER() OVER(PARTITION BY student_id ORDER BY created_at DESC) as rn
                FROM internship_requests
            ) r ON s.student_id = r.student_id AND r.rn = 1
            ORDER BY s.student_code ASC";
            
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
