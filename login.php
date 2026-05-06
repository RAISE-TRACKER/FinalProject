<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Check if email exists (PREPARED STATEMENT)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['register_error'] = 'Email is already registered!';
            $_SESSION['active_form'] = 'register';
        } else {
            // Insert new user (PREPARED STATEMENT)
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            
            $_SESSION['register_success'] = 'Account created successfully! Please login.';
            $_SESSION['active_form'] = 'login';
        }
    } catch(PDOException $e) {
        $_SESSION['register_error'] = 'Registration failed. Please try again.';
        $_SESSION['active_form'] = 'register';
    }

    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Fetch user (PREPARED STATEMENT)
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Incorrect email or password';
            $_SESSION['active_form'] = 'login';
        }
    } catch(PDOException $e) {
        $_SESSION['login_error'] = 'Login failed. Please try again.';
        $_SESSION['active_form'] = 'login';
    }

    header("Location: index.php");
    exit();
}
?>