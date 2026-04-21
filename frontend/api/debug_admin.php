<?php
require_once 'db.php';

echo "Database Type: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n\n";

echo "--- User 'admin' details ---\n";
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute(['admin']);
$user = $stmt->fetch();
var_dump($user);

echo "\n--- Table Structure (users) ---\n";
if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql') {
    $stmt = $pdo->query("DESCRIBE users");
} else {
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'users'");
}
while ($row = $stmt->fetch()) {
    print_r($row);
}
?>
