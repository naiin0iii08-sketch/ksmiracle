<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Get count of staffMembers
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM staff");
    $result = $stmt->fetch();
    
    // Get list of departments as sample
    $stmt_list = $pdo->query("SELECT DISTINCT department FROM staff LIMIT 5");
    $list = $stmt_list->fetchAll();

    echo json_encode([
        'count' => $result['total'],
        'departments' => $list
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
