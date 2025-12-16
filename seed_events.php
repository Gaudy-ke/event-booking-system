<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Seeding events with refined images...<br>";
    
    // Clear existing events
    $conn->exec("DELETE FROM events");
    echo "✓ Cleared old events<br>";
    
    $events = [
        [
            'The Grand Music Night',
            'Experience an unforgettable night of live music featuring top local and international artists.',
            '2025-12-31', '19:00:00',
            'Uhuru Gardens',
            2000.00, 500, 480,
            'https://cdn.pixabay.com/photo/2018/06/17/10/38/artist-3480274_1280.jpg'
        ],
        [
            'Tech Conference 2025',
            'Join industry leaders and innovators for a day of inspiring talks and networking.',
            '2025-11-15', '09:00:00',
            'Nairobi Convention Center',
            3500.00, 300, 250,
            'https://cdn.pixabay.com/photo/2016/11/22/19/15/hand-1850120_1280.jpg'
        ],
        [
            'Annual Food Festival',
            'A culinary journey through diverse cuisines with tastings, workshops, and chef demos.',
            '2026-01-20', '10:00:00',
            'KICC Grounds',
            1000.00, 1000, 950,
            'https://cdn.pixabay.com/photo/2016/11/21/16/01/woman-1846127_1280.jpg'
        ],
        [
            'Jazz Evening',
            'Relax with smooth jazz under the stars. Perfect for a chill night out.',
            '2025-12-10', '18:30:00',
            'Karen Blixen Museum',
            2500.00, 150, 120,
            'https://cdn.pixabay.com/photo/2017/07/21/23/57/concert-2527495_1280.jpg'
        ]
    ];
    
    $sql = "INSERT INTO events (event_name, description, event_date, event_time, venue, price, total_seats, available_seats, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    foreach($events as $event) {
        $stmt->execute($event);
        echo "✓ Inserted: {$event[0]}<br>";
    }
    
    echo "<br><strong style='color: green;'>Seeding completed successfully!</strong>";
    echo "<br><a href='pages/events.php'>View Events</a>";
    
} catch(PDOException $e) {
    echo "<strong style='color: red;'>Error:</strong> " . $e->getMessage();
}
?>
