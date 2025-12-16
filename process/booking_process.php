<?php
include '../config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $num_tickets = $_POST['num_tickets'];
    $total_amount = $_POST['total_amount'];
    
    $db = new Database();
    $conn = $db->getConnection();
    
    try {
        $conn->beginTransaction();
        
        // 1. Check availability again
        $check_sql = "SELECT available_seats FROM events WHERE event_id = ? FOR UPDATE";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$event_id]);
        $event = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if($event['available_seats'] < $num_tickets) {
            throw new Exception("Not enough seats available!");
        }
        
        // 2. Insert booking
        $booking_sql = "INSERT INTO bookings (user_id, event_id, num_tickets, total_amount, status) VALUES (?, ?, ?, ?, 'confirmed')";
        $booking_stmt = $conn->prepare($booking_sql);
        $booking_stmt->execute([$user_id, $event_id, $num_tickets, $total_amount]);
        
        // 3. Update available seats
        $update_sql = "UPDATE events SET available_seats = available_seats - ? WHERE event_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$num_tickets, $event_id]);
        
        $conn->commit();
        
        $_SESSION['success'] = "Booking confirmed successfully!";
        header("Location: ../pages/dashboard.php");
        
    } catch(Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Booking failed: " . $e->getMessage();
        header("Location: ../pages/events.php");
    }
} else {
    header("Location: ../pages/events.php");
}
?>
