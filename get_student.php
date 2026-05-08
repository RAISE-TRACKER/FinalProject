<?php
require_once 'config.php';

$stmt = $pdo->prepare("
    SELECT *
    FROM students
");

$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($students);
?>