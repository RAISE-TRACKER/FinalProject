<?php
require_once 'config.php';

header('Content-Type: application/json');
requireApiLogin();

$id = (int)$_GET['id'] ?? 0;
$teacher_id = (int)$_SESSION['user_id'];