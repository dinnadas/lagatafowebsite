<?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "lagatafo"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Add this query to check for new bookings in the database
$newBookingSql = "SELECT COUNT(*) AS newBookings FROM bookings WHERE is_new = 1"; // Assuming `is_new` is set to 1 for new bookings
$newBookingResult = $conn->query($newBookingSql);
$newBookingCount = 0;
if ($newBookingResult) {
    $newBookingCount = $newBookingResult->fetch_assoc()['newBookings'];
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
