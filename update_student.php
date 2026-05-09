<?php
require_once 'config.php';

header('Content-Type: application/json');
requireApiLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = (int)$_POST['id'] ?? 0;
        $participation = (int)$_POST['participation'] ?? 0;
        $teacher_id = (int)$_SESSION['user_id'];
        
        if ($id <= 0 || $participation < 0 || $participation > 100) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE students SET participation = ? WHERE id = ? AND teacher_id = ?");
        $stmt->execute([$participation, $id, $teacher_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Student not found']);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>