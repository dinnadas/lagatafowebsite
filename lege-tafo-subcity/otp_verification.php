<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = trim($_POST['otp']);   
    if ($enteredOtp == $_SESSION['otp']) {
        $userData = $_SESSION['userData'];
        $conn = new mysqli('localhost', 'root', '', 'lagatafo');
        if ($conn->connect_error){
            die('Connection failed: ' . $conn->connect_error);}
        $stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $userData['firstName'], $userData['lastName'], $userData['gender'], $userData['email'], $userData['password'], $userData['number']);
        if ($stmt->execute()) {
            echo "Successfully Registered!";
            unset($_SESSION['otp']);
            unset($_SESSION['userData']);
        } else {
            echo "Registration failed: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
    <form method="POST" action="otp_verification.php">
        <label for="otp">Enter OTP:</label><br>
        <input type="text" name="otp" id="otp" required><br><br>
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>