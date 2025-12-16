<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Attempting to add image column...<br>";
    
    // Add image_url column
    $conn->exec("ALTER TABLE events ADD COLUMN IF NOT EXISTS image_url VARCHAR(255)");
    echo "✓ Added/Checked image_url column<br>";
    
    echo "<br><strong style='color: green;'>Schema update completed successfully!</strong>";
    
} catch(PDOException $e) {
    echo "<strong style='color: red;'>Error:</strong> " . $e->getMessage();
}
?>
