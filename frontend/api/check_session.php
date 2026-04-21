<?php
header('Content-Type: application/json');
require_once 'auth_check.php';

if (isset($_SESSION['user_id'])) {
    echo json_encode(['authorized' => true, 'role' => $_SESSION['user_role']]);
} else {
    echo json_encode(['authorized' => false]);
}
?>
