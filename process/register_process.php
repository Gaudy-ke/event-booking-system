<?php
include '../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if user already exists
    $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([$username, $email]);
    
    if($check_stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username or email already exists!";
        header("Location: ../pages/register.php");
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $insert_sql = "INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    
    if($insert_stmt->execute([$username, $email, $hashed_password, $full_name, $phone])) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../pages/login.php");
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../pages/register.php");
    }
} else {
    header("Location: ../pages/register.php");
}
?>