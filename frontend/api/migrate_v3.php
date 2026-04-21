<?php
require_once 'db.php';

try {
    // Add gender to students
    $sql1 = "ALTER TABLE students ADD COLUMN IF NOT EXISTS gender ENUM('ชาย', 'หญิง') AFTER last_name";
    $pdo->exec($sql1);
    
    // Ensure year column is there (it is, but good to check)
    $sql2 = "ALTER TABLE students MODIFY COLUMN year INT DEFAULT 1";
    $pdo->exec($sql2);

    echo "Migration successful: gender column added/verified.";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
