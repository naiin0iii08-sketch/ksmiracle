<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only admins can see the list
requireAdmin();

try {
    // Get count of admins
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $result = $stmt->fetch();
    
    // Get list of admins with details from admins table
    $stmt = $pdo->query("SELECT u.id as user_id, u.username, u.email, u.status, a.admin_code, a.first_name, a.last_name 
                        FROM users u 
                        JOIN admins a ON u.id = a.user_id 
                        WHERE u.role = 'admin'
                        ORDER BY a.id ASC");
    
    $list = $stmt->fetchAll();

    echo json_encode([
        'count' => $result['total'],
        'admins' => $list
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
