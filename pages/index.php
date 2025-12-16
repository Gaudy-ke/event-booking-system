<?php include '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <section class="hero">
            <h1>Welcome to Event Booking System</h1>
            <p>Discover and book amazing events in your city</p>
            <a href="events.php" class="btn-primary">Browse Events</a>
        </section>

        <section class="featured-events">
            <h2>Featured Events</h2>
            <div class="events-grid">
                <?php
                $db = new Database();
                $conn = $db->getConnection();
                
                $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date LIMIT 3";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $events = $stmt->fetchAll();
                
                if(count($events) > 0) {
                    foreach($events as $event) {
                        echo "
                        <div class='event-card'>
                            <h3>{$event['event_name']}</h3>
                            <p class='event-date'>{$event['event_date']} at {$event['event_time']}</p>
                            <p class='event-venue'>{$event['venue']}</p>
                            <p class='event-price'>KSh. {$event['price']}</p>
                            <p class='event-seats'>{$event['available_seats']} seats available</p>
                            <a href='booking.php?event_id={$event['event_id']}' class='btn-book'>Book Now</a>
                        </div>";
                    }
                } else {
                    echo "<p>No upcoming events found.</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>