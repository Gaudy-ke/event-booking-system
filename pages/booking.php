<?php include '../config/database.php'; 
// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : 0;
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$event) {
    echo "Event not found!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - <?php echo $event['event_name']; ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        function calculateTotal() {
            var price = <?php echo $event['price']; ?>;
            var tickets = document.getElementById('num_tickets').value;
            var total = price * tickets;
            document.getElementById('total_amount').innerText = "KSh. " + total.toFixed(2);
            document.getElementById('total_input').value = total;
        }
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <div class="form-container">
            <h2>Complete Booking</h2>
            
            <div class="event-summary">
                <h3><?php echo $event['event_name']; ?></h3>
                <p><strong>Venue:</strong> <?php echo $event['venue']; ?></p>
                <p><strong>Price per Ticket:</strong> KSh. <?php echo $event['price']; ?></p>
                <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?></p>
            </div>
            
            <form action="../process/booking_process.php" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                <input type="hidden" name="total_amount" id="total_input" value="<?php echo $event['price']; ?>">
                
                <div class="form-group">
                    <label for="num_tickets">Number of Tickets:</label>
                    <input type="number" id="num_tickets" name="num_tickets" min="1" max="<?php echo $event['available_seats']; ?>" value="1" onchange="calculateTotal()" required>
                </div>
                
                <div class="total-section">
                    <h3>Total Amount: <span id="total_amount">KSh. <?php echo $event['price']; ?></span></h3>
                </div>
                
                <button type="submit" class="btn-primary">Confirm Booking</button>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
