<?php
require_once 'db.php';

try {
    echo "Starting database fix...\n";
    
    // 1. Alter table to include 'admin' role
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'teacher') NOT NULL");
    echo "✅ Table 'users' altered to include 'admin' role.\n";
    
    // 2. Clear old broken admin entries if any and set correct one
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Try to update existing 'admin' username
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin', password = ?, status = 'active' WHERE username = 'admin'");
    $stmt->execute([$hashed_password]);
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Admin user role and password updated successfully.\n";
    } else {
        // If not found, insert fresh
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, status) VALUES ('admin', ?, 'admin', 'admin@swu.ac.th', 'active')");
        $stmt->execute([$hashed_password]);
        echo "✅ Fresh admin user created successfully.\n";
    }

    echo "🎉 Fix complete! Please try logging in again.\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
