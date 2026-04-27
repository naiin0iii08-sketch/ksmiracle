<?php
/**
 * Excel Export API (PHP Server-side) - Updated for better stability
 */
require_once 'db.php';

// ป้องกัน output ที่ไม่พึงประสงค์
ob_start();

// รับค่าประเภทข้อมูล (student, teacher, logs)
$type = $_GET['type'] ?? 'student';
$date = date('d-m-Y');
$filename = "SWU_Export_{$type}_{$date}.xls";

// กำหนดหัวข้อและ Query ตามประเภทที่เลือก
if ($type === 'student') {
    $title = "รายชื่อนิสิต (Students List)";
    $headers = ["รหัสนิสิต", "ชื่อ", "นามสกุล", "คณะ/สาขาวิชา", "อีเมล"];
    $query = "SELECT s.student_code, s.first_name, s.last_name, s.major, u.email 
              FROM students s 
              JOIN users u ON s.user_id = u.id 
              ORDER BY u.id DESC";
} elseif ($type === 'teacher') {
    $title = "รายชื่อคณาจารย์ (Teachers List)";
    $headers = ["รหัสบุคลากร", "ชื่อ", "นามสกุล", "ภาควิชา", "อีเมล"];
    $query = "SELECT t.staff_code, t.first_name, t.last_name, t.department, u.email 
              FROM teachers t 
              JOIN users u ON t.user_id = u.id 
              ORDER BY u.id DESC";
} elseif ($type === 'requests') {
    $title = "รายงานความจำนงขอฝึกงาน (Internship Requests Report)";
    $headers = ["รหัสนิสิต", "ชื่อ-นามสกุล", "สาขาวิชา", "สถานประกอบการ", "ตำแหน่ง", "ระยะเวลา", "สถานะ"];
    
    // PRODUCTION: PostgreSQL (Render)
    if ($db_url) {
        $query = "SELECT s.student_code, (s.first_name || ' ' || s.last_name) as full_name, s.major, r.company_name, r.position, (r.start_date || ' ถึง ' || r.end_date) as duration, r.status 
                  FROM internship_requests r 
                  JOIN students s ON r.student_id = s.student_id 
                  ORDER BY r.id DESC";
    } else {
        // LOCAL: MySQL (XAMPP)
        $query = "SELECT s.student_code, CONCAT(s.first_name, ' ', s.last_name) as full_name, s.major, r.company_name, r.position, CONCAT(r.start_date, ' ถึง ', r.end_date) as duration, r.status 
                  FROM internship_requests r 
                  JOIN students s ON r.student_id = s.id 
                  ORDER BY r.id DESC";
    }
} else {
    $title = "ประวัติการเข้าใช้งาน (Login Logs)";
    $headers = ["Username", "วันเวลา", "IP Address", "สถานะ"];
    $query = "SELECT username, login_time, ip_address, status 
              FROM login_logs 
              ORDER BY id DESC";
}

try {
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ล้าง buffer ก่อนหน้าเพื่อให้แน่ใจว่าไฟล์สะอาด
    ob_clean();

    // ตั้งค่า Header บังคับดาวน์โหลดเป็น Excel
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // จัดการเรื่องภาษาไทย (UTF-8 BOM)
    echo "\xEF\xBB\xBF";

    // เริ่มต้นสร้างตารางแบบ Excel Style
    echo "<h3>$title</h3>";
    echo "<p>วันที่ส่งออก: " . date('d/m/Y H:i') . "</p>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    
    // สร้างหัวตาราง (Thead)
    echo "<thead><tr style='background-color: #d93d25; color: #ffffff;'>";
    foreach ($headers as $h) {
        echo "<th>$h</th>";
    }
    echo "</tr></thead>";

    // ใส่ข้อมูล (Tbody)
    echo "<tbody>";
    if (count($data) > 0) {
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                // จัดรูปแบบเฉพาะถ้าเป็นรหัสยาวๆ ไม่ให้เป็นเลขยกกำลังด้วย CSS mso-number-format
                $style = (is_numeric($cell) && strlen($cell) > 5) ? "style='mso-number-format:\"\\@\";'" : "";
                echo "<td $style>$cell</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='".count($headers)."' align='center'>ไม่พบข้อมูลในระบบ</td></tr>";
    }
    echo "</tbody>";
    echo "</table>";

} catch (Exception $e) {
    // หาก Error ให้แสดงเป็น JSON หรือข้อความปกติ
    header('Content-Type: text/html; charset=utf-8');
    echo "<h3>เกิดข้อผิดพลาดในการดึงข้อมูล:</h3> " . $e->getMessage();
}
ob_end_flush();
