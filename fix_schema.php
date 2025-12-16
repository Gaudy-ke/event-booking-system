<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Attempting to fix database schema...<br>";
    
    // Add full_name column if it doesn't exist
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS full_name VARCHAR(100)");
    echo "✓ Added/Checked full_name column<br>";
    
    // Add phone column if it doesn't exist
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20)");
    echo "✓ Added/Checked phone column<br>";
    
    echo "<br><strong style='color: green;'>Schema fix completed successfully!</strong>";
    
} catch(PDOException $e) {
    echo "<strong style='color: red;'>Error:</strong> " . $e->getMessage();
}
?>
