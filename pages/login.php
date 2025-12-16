<?php include '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Booking System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            
            <?php
            if(isset($_SESSION['error'])) {
                echo "<div class='error-message'>{$_SESSION['error']}</div>";
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])) {
                echo "<div class='success-message'>{$_SESSION['success']}</div>";
                unset($_SESSION['success']);
            }
            ?>
            
            <form action="../process/login_process.php" method="POST">
                <div class="form-group">
                    <label for="username_email">Username or Email:</label>
                    <input type="text" id="username_email" name="username_email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Login</button>
            </form>
            
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
