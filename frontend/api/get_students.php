<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Get count of students
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM students");
    $result = $stmt->fetch();
    
    // Get list of students with email from users table
    $stmt = $pdo->query("SELECT u.id as user_id, u.username, u.email, u.status, s.student_code, s.first_name, s.last_name, s.major 
                        FROM users u 
                        JOIN students s ON u.id = s.user_id 
                        WHERE u.role = 'student'");
    
    $list = $stmt->fetchAll();

    echo json_encode([
        'count' => $result['total'],
        'students' => $list
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
