<?php
require_once 'db.php';

try {
    // Get some students
    $stmt = $pdo->query("SELECT student_id FROM students LIMIT 3");
    $students = $stmt->fetchAll();

    if (count($students) === 0) {
        die("No students found to seed data.");
    }

    $data = [
        [
            'student_id' => $students[0]['student_id'],
            'company_name' => 'บริษัท กสิกรไทย จำกัด (มหาชน)',
            'position' => 'Software Developer Trainee',
            'start_date' => '2026-06-01',
            'end_date' => '2026-10-31',
            'status' => 'advisor_approved'
        ],
        [
            'student_id' => count($students) > 1 ? $students[1]['student_id'] : $students[0]['student_id'],
            'company_name' => 'Garena Online (Thailand)',
            'position' => 'UX/UI Designer Intern',
            'start_date' => '2026-07-15',
            'end_date' => '2026-11-15',
            'status' => 'pending'
        ],
        [
            'student_id' => count($students) > 2 ? $students[2]['student_id'] : $students[0]['student_id'],
            'company_name' => 'Agoda Services Co., Ltd.',
            'position' => 'Data Analyst Intern',
            'start_date' => '2026-05-20',
            'end_date' => '2026-09-20',
            'status' => 'letter_issued'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO internship_requests (student_id, company_name, position, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($data as $row) {
        $stmt->execute([$row['student_id'], $row['company_name'], $row['position'], $row['start_date'], $row['end_date'], $row['status']]);
    }

    echo "Seed successful: 3 mock requests added.";
} catch (PDOException $e) {
    echo "Seed failed: " . $e->getMessage();
}
?>
