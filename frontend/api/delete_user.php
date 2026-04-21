<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only admins can delete users
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'Missing user ID']);
    exit;
}

try {
    // We only need to delete from the users table.
    // The foreign keys in students and teachers are set to ON DELETE CASCADE.
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => 'User deleted successfully']);
    } else {
        echo json_encode(['error' => 'User not found or already deleted']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
