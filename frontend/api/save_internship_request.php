<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth_check.php';

// Only students can submit internship requests
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'เฉพาะนิสิตเท่านั้นที่สามารถบันทึกข้อมูลได้']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูลผู้ใช้งานในเซสชัน']);
    exit;
}

// Get the numeric student_id from the students table
try {
    $stmt = $pdo->prepare("SELECT student_id FROM students WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูลนิสิตสำหรับผู้ใช้งานนี้']);
        exit;
    }
    
    $student_numeric_id = $student['student_id'];
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

$company_name = $data['company_name'] ?? '';
$position = $data['position'] ?? '';
$start_date = $data['start_date'] ?? null;
$end_date = $data['end_date'] ?? null;

if (empty($company_name) || empty($position) || !$start_date || !$end_date) {
    echo json_encode(['success' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO internship_requests (student_id, company_name, position, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$student_numeric_id, $company_name, $position, $start_date, $end_date]);

    echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลความจำนงขอฝึกงานเรียบร้อยแล้ว']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
}
?>
