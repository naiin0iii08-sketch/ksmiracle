<?php
// Database configuration for PostgreSQL (Production/Render)
// Prefer DATABASE_URL from Render environment variables

$db_url = getenv('DATABASE_URL');

if ($db_url) {
    // Parse the DATABASE_URL (format: postgres://user:pass@host:port/dbname)
    $db_parts = parse_url($db_url);
    $host = $db_parts['host'];
    $port = $db_parts['port'] ?? 5432;
    $dbname = ltrim($db_parts['path'], '/');
    $username = $db_parts['user'];
    $password = $db_parts['pass'];
} else {
    // Fallback to individual local environment variables or defaults
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: 'internship_system';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
}

try {
    if ($db_url) {
        // PRODUCTION: Using pgsql for PostgreSQL on Render
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    } else {
        // LOCAL: Using mysql for MariaDB/MySQL on XAMPP
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    }
    
    $pdo = new PDO($dsn, $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
