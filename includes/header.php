<?php
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<header>
    <nav class="navbar">
        <div class="nav-brand">
            <h2>EventBook</h2>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <?php if($isLoggedIn): ?>
                <a href="dashboard.php">Dashboard</a>
                <span>Welcome, <?php echo $username; ?></span>
                <a href="../process/logout_process.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>