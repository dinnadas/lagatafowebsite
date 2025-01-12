<?php
    // Retrieve and sanitize POST data
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $number = trim($_POST['number']);

    // Validate inputs (no extra checks)
    if (empty($firstName) || empty($lastName) || empty($gender) || empty($email) || empty($password) || empty($number)) {
        die("All fields are required.");
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'lagatafo');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    } else {
        // Check if the email already exists
        $checkQuery = "SELECT * FROM registration WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "This email is already registered. Please use a different email.";
            $stmt->close();
            $conn->close();
            exit; // Stop further execution
        }

        // Prepare SQL statement for registration
        $stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die('Error in SQL statement: ' . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sssssi", $firstName, $lastName, $gender, $email, $password, $number);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Successfully Registered!";
        } else {
            echo "Registration failed: " . $stmt->error;
        }

        // Close connections
        $stmt->close();
        $conn->close();
    }
?>
