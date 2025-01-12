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
            echo "
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .popup-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999;
                    animation: fadeIn 0.3s ease-in-out;
                }
                .popup {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(to right, #4CAF50, #81C784);
                    padding: 30px;
                    border-radius: 15px;
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                    color: white;
                    text-align: center;
                    z-index: 1000;
                    animation: popupScale 0.3s ease-in-out;
                }
                .popup h2 {
                    margin: 0 0 10px;
                    font-size: 24px;
                }
                .popup button {
                    margin-top: 15px;
                    padding: 10px 20px;
                    border: none;
                    background-color: #ffffff;
                    color: #4CAF50;
                    border-radius: 25px;
                    font-size: 16px;
                    cursor: pointer;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                }
                .popup button:hover {
                    background-color: #f1f1f1;
                    color: #388E3C;
                }
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }
                @keyframes popupScale {
                    from {
                        transform: translate(-50%, -50%) scale(0.8);
                    }
                    to {
                        transform: translate(-50%, -50%) scale(1);
                    }
                }
            </style>
            <div class='popup-overlay'></div>
            <div class='popup'>
                <h2>🎉 Successfully Registered!</h2>
                <p>Thank you for signing up. You can now enjoy our services.</p>
                <button onclick='closePopup()'>OK</button>
            </div>
            <script>
                function closePopup() {
                    document.querySelector('.popup').style.display = 'none';
                    document.querySelector('.popup-overlay').style.display = 'none';
                    window.location.href = 'registration.html'; // Redirect to another page
                }
            </script>
            ";
        } else {
            echo "
            <style>
                .popup-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999;
                    animation: fadeIn 0.3s ease-in-out;
                }
                .popup {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(to right, #F44336, #FF7961);
                    padding: 30px;
                    border-radius: 15px;
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                    color: white;
                    text-align: center;
                    z-index: 1000;
                    animation: popupScale 0.3s ease-in-out;
                }
                .popup h2 {
                    margin: 0 0 10px;
                    font-size: 24px;
                }
                .popup button {
                    margin-top: 15px;
                    padding: 10px 20px;
                    border: none;
                    background-color: #ffffff;
                    color: #F44336;
                    border-radius: 25px;
                    font-size: 16px;
                    cursor: pointer;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                }
                .popup button:hover {
                    background-color: #f1f1f1;
                    color: #D32F2F;
                }
            </style>
            <div class='popup-overlay'></div>
            <div class='popup'>
                <h2>❌ Registration Failed</h2>
                <p>Error: " . htmlspecialchars($stmt->error) . "</p>
                <button onclick='closePopup()'>Try Again</button>
            </div>
            <script>
                function closePopup() {
                    document.querySelector('.popup').style.display = 'none';
                    document.querySelector('.popup-overlay').style.display = 'none';
                    window.location.href = 'registration.html'; // Redirect to the registration page
                }
            </script>
            ";
        }
        
        

        // Close connections
        $stmt->close();
        $conn->close();
    }
?>
