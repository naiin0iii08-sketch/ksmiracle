<?php
require_once 'db.php';

echo "<h1>Database Migration - Internship Status Update</h1>";

try {
    // Update the ENUM values for internship_requests status
    $sql = "ALTER TABLE internship_requests MODIFY COLUMN status ENUM('pending', 'advisor_approved', 'letter_issued', 'completed', 'cancelled') DEFAULT 'pending'";
    $pdo->exec($sql);
    
    echo "<p style='color: green;'>✅ Successfully updated status ENUM for internship_requests.</p>";
    echo "<p>New values: 'pending', 'advisor_approved', 'letter_issued', 'completed', 'cancelled'</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Migration failed: " . $e->getMessage() . "</p>";
    echo "<p>If the error says 'Invalid use of NULL value', ensure all current status values are compatible with the new ENUM.</p>";
}
?>
