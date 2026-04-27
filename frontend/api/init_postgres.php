<?php
require_once 'db.php';

header('Content-Type: text/plain');

if (!getenv('DATABASE_URL')) {
    die("This script is intended for production/Render environment with DATABASE_URL set.");
}

echo "Starting Database Initialization for PostgreSQL...\n";

try {
    $sql = file_get_contents('../database_postgres.sql');
    if (!$sql) {
        throw new Exception("Could not read database_postgres.sql file.");
    }

    // PostgreSQL doesn't support multiple statements in one exec() easily if there are errors
    // But PDO exec() should handle it. However, let's try to split by semicolon if possible
    // Actually, for PostgreSQL, it's better to run it as one block.
    
    $pdo->exec($sql);
    
    echo "✅ Database initialized successfully!\n";
    echo "Created tables: users, students, teachers, admins, login_logs.\n";
    echo "Initial admin user created: admin / admin123\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
