<?php
require_once 'db.php';
$stmt = $pdo->query("SELECT * FROM login_logs ORDER BY login_time DESC LIMIT 10");
$logs = $stmt->fetchAll();
echo "--- Last 10 Login Logs ---\n";
foreach ($logs as $log) {
    echo "[" . $log['login_time'] . "] User: " . $log['username'] . " | IP: " . $log['ip_address'] . " | Status: " . $log['status'] . "\n";
}

$stmt = $pdo->query("SELECT id, username, role, status FROM users");
$users = $stmt->fetchAll();
echo "\n--- All Users in DB ---\n";
foreach ($users as $user) {
    echo "ID: " . $user['id'] . " | User: " . $user['username'] . " | Role: " . $user['role'] . " | Status: " . $user['status'] . "\n";
}
?>
