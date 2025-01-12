<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lagatafo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_panel.php");
    exit;
}

$id = $_GET['id'];

// Delete user
$sql = "DELETE FROM registration WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin_panel.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
