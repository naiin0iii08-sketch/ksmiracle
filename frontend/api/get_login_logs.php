<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Get the latest 50 login attempts
    $query = "SELECT username, login_time, ip_address, status 
              FROM login_logs 
              ORDER BY login_time DESC 
              LIMIT 50";
    
    $stmt = $pdo->query($query);
    $logs = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'logs' => $logs
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch logs: ' . $e->getMessage()]);
}
?>
