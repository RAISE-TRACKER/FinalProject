<?php
require_once 'config.php';
header('Content-Type: application/json');

/*
JOIN is used to combine students with their teacher information.
It links students.teacher_id to users.id so we can get teacher details (name and email) for each student in one query.
*/

try {
    $stmt = $pdo->prepare("
        SELECT
            s.id,
            s.student_id,
            s.name,
            s.section_code,
            s.participation,
            u.id AS teacher_id,
            u.name AS teacher_name,
            u.email AS teacher_email
        FROM students s
        INNER JOIN users u ON s.teacher_id = u.id
        ORDER BY s.name
    ");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($students);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>