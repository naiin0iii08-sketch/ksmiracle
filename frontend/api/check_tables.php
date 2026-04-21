<?php
require_once 'db.php';
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Existing tables: " . implode(", ", $tables) . "\n";
?>
