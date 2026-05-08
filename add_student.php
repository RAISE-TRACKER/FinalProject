<?php
require_once 'config.php';

header('Content-Type: application/json');
requireApiLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $student_id = trim($_POST['student_id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $section_code = trim($_POST['section_code'] ?? '');
        $teacher_id = (int)$_SESSION['user_id'];
        
        if (empty($student_id) || empty($name) || empty($section_code)) {
            http_response_code(400);
            echo json_encode(['error' => 'Student ID, Name, and Section Code are required']);
            exit;
        }

        // Check if student ID already exists for this teacher
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE student_id = ? AND teacher_id = ?");
        $checkStmt->execute([$student_id, $teacher_id]);
        if ($checkStmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Student ID already exists']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO students (teacher_id, student_id, name, section_code, participation) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$teacher_id, $student_id, $name, $section_code]);
        
        http_response_code(201);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>