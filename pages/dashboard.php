<?php include '../config/database.php'; 
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Booking System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="dashboard-header">
            <h1>My Dashboard</h1>
        </div>

        <?php
        $db = new Database();
        $conn = $db->getConnection();
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT b.*, e.event_name, e.event_date, e.event_time, e.venue, e.image_url 
                FROM bookings b 
                JOIN events e ON b.event_id = e.event_id 
                WHERE b.user_id = ? 
                ORDER BY b.booking_date DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $bookings = $stmt->fetchAll();
        
        // Calculate stats
        $total_bookings = count($bookings);
        $total_spent = 0;
        foreach($bookings as $b) {
            $total_spent += $b['total_amount'];
        }
        ?>

        <!-- Stats Section -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">🎟️</div>
                <div class="stat-info">
                    <h3>Total Bookings</h3>
                    <p><?php echo $total_bookings; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Total Spent</h3>
                    <p>KSh. <?php echo number_format($total_spent); ?></p>
                </div>
            </div>
        </div>
        
        <h2 style="margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">Recent Bookings</h2>

        <?php if(count($bookings) > 0): ?>
            <div class="bookings-grid">
                <?php foreach($bookings as $booking): 
                    $status_class = 'status-' . strtolower($booking['status']);
                ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <span class="booking-status <?php echo $status_class; ?>"><?php echo $booking['status']; ?></span>
                        <span style="color: var(--text-muted); font-size: 0.8rem;"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></span>
                    </div>
                    <div class="booking-body">
                        <h3 class="booking-title"><?php echo $booking['event_name']; ?></h3>
                        <div class="booking-detail">
                            📅 <span><?php echo date('D, M d Y', strtotime($booking['event_date'])); ?></span>
                        </div>
                        <div class="booking-detail">
                            ⏰ <span><?php echo date('H:i', strtotime($booking['event_time'])); ?></span>
                        </div>
                        <div class="booking-detail">
                            📍 <span><?php echo $booking['venue']; ?></span>
                        </div>
                        <div class="booking-detail">
                            🎟️ <span><?php echo $booking['num_tickets']; ?> Tickets</span>
                        </div>
                    </div>
                    <div class="booking-footer">
                        <span>Total Paid</span>
                        <span class="booking-price">KSh. <?php echo number_format($booking['total_amount']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>You haven't booked any events yet. <a href="events.php" style="color: var(--accent-color);">Browse Events</a></p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
