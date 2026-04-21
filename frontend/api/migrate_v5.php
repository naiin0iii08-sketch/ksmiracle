<?php
require_once 'db.php';

echo "<h1>Database Migration - Supervision Notes</h1>";

try {
    // Add supervision_note to internship_requests
    $sql = "ALTER TABLE internship_requests ADD COLUMN IF NOT EXISTS supervision_note TEXT AFTER status";
    $pdo->exec($sql);
    
    echo "<p style='color: green;'>✅ Successfully added supervision_note column to internship_requests.</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Migration failed: " . $e->getMessage() . "</p>";
}
?>
