<?php
/**
 * Automated Database Setup Script for Internship Management System
 * Run this by visiting: http://localhost/ksmiracle/frontend/api/setup_db.php
 */

$host = 'localhost';
$root_user = 'root';
$root_pass = ''; // Default XAMPP/WAMP
$dbname = 'internship_system';

echo "<h1>SWU Internship System - Database Setup</h1>";
echo "<hr>";

try {
    // 1. Connect to MySQL Server
    $pdo = new PDO("mysql:host=$host", $root_user, $root_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Connected to MySQL Server successfully.</p>";

    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "<p style='color: green;'>✅ Database '<b>$dbname</b>' created or already exists.</p>";

    // 3. Connect to the specific database
    $pdo->exec("USE `$dbname`;");

    // 4. Read database.sql file
    $sql_file = '../database.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found at: $sql_file");
    }

    $sql_content = file_get_contents($sql_file);
    
    // 5. Execute SQL content
    // We split by semicolon to execute one by one (simplified)
    // Note: This won't work perfectly if semicolons are inside strings, 
    // but for our clean database.sql it should be fine.
    $queries = explode(';', $sql_content);
    
    $success_count = 0;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $pdo->exec($query);
            $success_count++;
        }
    }

    echo "<p style='color: green;'>✅ Executed $success_count SQL commands successfully.</p>";
    echo "<hr>";
    echo "<h2>🎉 Setup Complete!</h2>";
    echo "<p>Your database is ready. You can now use the credentials below to log in:</p>";
    echo "<ul>";
    echo "<li><b>URL:</b> <a href='../login.html'>Login Page</a></li>";
    echo "<li><b>Username:</b> admin</li>";
    echo "<li><b>Password:</b> admin123</li>";
    echo "</ul>";
    echo "<p><a href='../user_management.html' style='padding: 10px 20px; background: #d93d25; color: white; text-decoration: none; border-radius: 5px;'>Go to Admin Dashboard</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ ERROR: " . $e->getMessage() . "</p>";
    echo "<p>Please ensure XAMPP/WAMP is running and the 'root' user has no password.</p>";
}
?>
