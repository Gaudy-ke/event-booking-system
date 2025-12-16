<?php
// Database Setup Script for PostgreSQL (Neon)
// Run this file once to create the required tables

require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Create users table
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100),
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created successfully<br>";
    
    // Create events table
    $conn->exec("
        CREATE TABLE IF NOT EXISTS events (
            event_id SERIAL PRIMARY KEY,
            event_name VARCHAR(100) NOT NULL,
            description TEXT,
            event_date DATE NOT NULL,
            event_time TIME NOT NULL,
            venue VARCHAR(200) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            total_seats INT NOT NULL,
            available_seats INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Events table created successfully<br>";
    
    // Create bookings table
    $conn->exec("
        CREATE TABLE IF NOT EXISTS bookings (
            booking_id SERIAL PRIMARY KEY,
            user_id INT NOT NULL REFERENCES users(user_id),
            event_id INT NOT NULL REFERENCES events(event_id),
            num_tickets INT NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) DEFAULT 'confirmed'
        )
    ");
    echo "✓ Bookings table created successfully<br>";
    
    // Check if events table is empty, then insert sample data
    $stmt = $conn->query("SELECT COUNT(*) FROM events");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        $conn->exec("
            INSERT INTO events (event_name, description, event_date, event_time, venue, price, total_seats, available_seats) VALUES
            ('Tech Conference 2025', 'Annual technology conference featuring latest innovations', '2025-12-25', '09:00:00', 'Nairobi Convention Center', 2500.00, 200, 180),
            ('Music Festival', 'Live performances from top Kenyan artists', '2025-12-31', '18:00:00', 'Uhuru Gardens', 1500.00, 500, 450),
            ('Business Networking', 'Connect with entrepreneurs and investors', '2026-01-15', '14:00:00', 'Kempinski Hotel', 3000.00, 100, 85),
            ('Art Exhibition', 'Contemporary African art showcase', '2026-01-20', '10:00:00', 'National Museum', 500.00, 150, 150),
            ('Coding Bootcamp', 'Intensive 1-day web development workshop', '2026-02-01', '08:00:00', 'iHub Nairobi', 5000.00, 30, 25)
        ");
        echo "✓ Sample events inserted successfully<br>";
    } else {
        echo "ℹ Events table already has data, skipping sample data insertion<br>";
    }
    
    echo "<br><strong style='color: green;'>Database setup completed successfully!</strong>";
    echo "<br><br><a href='pages/index.php'>Go to Home Page</a>";
    
} catch(PDOException $e) {
    echo "<strong style='color: red;'>Error:</strong> " . $e->getMessage();
}
?>
