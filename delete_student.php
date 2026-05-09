<?php
require_once 'config.php';

header('Content-Type: application/json');
requireApiLogin();

$id = (int)$_GET['id'] ?? 0;
$teacher_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ? AND teacher_id = ?");
        $stmt->execute([$id, $teacher_id]);
        
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