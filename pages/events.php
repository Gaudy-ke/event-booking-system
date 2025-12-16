<?php include '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - Event Booking System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>All Events</h1>
        
        <div class="events-container">
            <?php
            $db = new Database();
            $conn = $db->getConnection();
            
            $sql = "SELECT * FROM events WHERE event_date >= CURRENT_DATE ORDER BY event_date";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $events = $stmt->fetchAll();
            
            if(count($events) > 0) {
                foreach($events as $event) {
                    $available_seats = $event['available_seats'];
                    $status_class = $available_seats > 20 ? 'available' : ($available_seats > 0 ? 'limited' : 'sold-out');
                    
                    echo "
                    <div class='event-card {$status_class}'>
                        <div class='event-image' style='height: 200px; overflow: hidden; border-radius: 8px 8px 0 0;'>
                            <img src='{$event['image_url']}' alt='{$event['event_name']}' style='width: 100%; height: 100%; object-fit: cover;'>
                        </div>
                        <h3>{$event['event_name']}</h3>
                        <p class='event-description'>{$event['description']}</p>
                        <div class='event-details'>
                            <p><strong>Date:</strong> {$event['event_date']}</p>
                            <p><strong>Time:</strong> {$event['event_time']}</p>
                            <p><strong>Venue:</strong> {$event['venue']}</p>
                            <p><strong>Price:</strong> KSh. {$event['price']}</p>
                            <p><strong>Seats Available:</strong> {$available_seats}</p>
                        </div>";
                        
                    if($available_seats > 0) {
                        echo "<a href='booking.php?event_id={$event['event_id']}' class='btn-book'>Book Tickets</a>";
                    } else {
                        echo "<button class='btn-sold-out' disabled>Sold Out</button>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<p>No upcoming events found.</p>";
            }
            ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>