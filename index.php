<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RaiseTrack</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-graduation-cap"></i>
                <h1>RaiseTrack</h1>
                <p>Welcome! Please sign in to your account.</p>
            </div>

            <!-- Login Form -->
            <div class="form-box active" id="login">
                <h3><i class="fas fa-sign-in-alt"></i> Sign In</h3>
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
                <?php endif; ?>
                
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="loginEmail"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="loginEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary full-width">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>
                <div class="form-links">
                    <p>Don't have an account? <a href="#" onclick="showForm('register')">Sign up here</a></p>
                </div>
            </div>

            <!-- Register Form -->
            <div class="form-box" id="register">
                <h3><i class="fas fa-user-plus"></i> Create Account</h3>
                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></div>
                <?php endif; ?>
                
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="regName"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" id="regName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="regEmail"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="regEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="regPassword"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" id="regPassword" name="password" minlength="6" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary full-width">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>
                <div class="form-links">
                    <p>Already have an account? <a href="#" onclick="showForm('login')">Sign in here</a></p>
                </div>
            </div>
        </div>

       
    </div>
     <script>
        function showForm(formId) {
            document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
            document.getElementById(formId).classList.add("active");
            
            // Clear previous errors
            document.querySelectorAll('.alert').forEach(alert => alert.remove());
        }
    </script>
</body>
</html>