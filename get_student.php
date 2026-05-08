<?php
require_once 'config.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
    SELECT *
    FROM students
");

$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($students);
?>