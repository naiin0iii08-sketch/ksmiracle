<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Get count of teachers
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM teachers");
    $result = $stmt->fetch();
    
    // Get list of teachers with email from users table
    $stmt = $pdo->query("SELECT u.id as user_id, u.username, u.email, u.status, t.staff_code, t.first_name, t.last_name, t.department 
                        FROM users u 
                        JOIN teachers t ON u.id = t.user_id 
                        WHERE u.role = 'teacher'");
    
    $list = $stmt->fetchAll();

    echo json_encode([
        'count' => $result['total'],
        'teachers' => $list
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
