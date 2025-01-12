<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lagatafo"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the booking ID is passed
if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Delete the booking
    $sql = "DELETE FROM bookings WHERE id = $bookingId";

    if ($conn->query($sql) === TRUE) {
        echo "Booking deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the bookings page
    header("Location: bookingss.php");
    exit();
}

$conn->close();
?>
