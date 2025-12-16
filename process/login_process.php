<?php
include '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if user exists (by username or email)
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username_email, $username_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user && password_verify($password, $user['password'])) {
        // Login success
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        header("Location: ../pages/dashboard.php");
    } else {
        // Login failed
        $_SESSION['error'] = "Invalid username/email or password!";
        header("Location: ../pages/login.php");
    }
} else {
    header("Location: ../pages/login.php");
}
?>
