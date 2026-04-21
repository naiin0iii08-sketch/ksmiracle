<?php
header('Content-Type: application/json');
// Set session cookie to last for 30 days
session_set_cookie_params(30 * 24 * 60 * 60);
session_start();
require_once 'db.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$ip_address = $_SERVER['REMOTE_ADDR'];

if (empty($username) || empty($password)) {
    echo json_encode(['error' => 'Please provide both username and password']);
    exit;
}

try {
    // 1. Check user in database (must be active)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 'active'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $status = 'Failed';
    $message = 'Invalid username or password';
    $login_success = false;

    if ($user && password_verify($password, $user['password'])) {
        $status = 'Success';
        $message = 'Login successful';
        $login_success = true;
        
        // Store user in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
    }

    // 2. Record the attempt in login_logs
    $log_stmt = $pdo->prepare("INSERT INTO login_logs (username, ip_address, status) VALUES (?, ?, ?)");
    $log_stmt->execute([$username, $ip_address, $status]);

    if ($login_success) {
        echo json_encode([
            'success' => true,
            'message' => $message,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $message]);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
