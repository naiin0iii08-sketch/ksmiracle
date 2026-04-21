<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized access. Admin privileges required.']);
        exit;
    }
}
?>
