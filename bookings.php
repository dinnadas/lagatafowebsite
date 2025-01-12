<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lagatafo"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $datetime = $conn->real_escape_string($_POST['datetime']);
    $message = $conn->real_escape_string($_POST['message']);

    $datetime = date("Y-m-d H:i:s"); // Get current date and time
    $sql = "INSERT INTO bookings (name, email, message, datetime) VALUES ('$name', '$email', '$message', '$datetime')";

    if ($conn->query($sql) === TRUE) {
        echo "<style>
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
                <h2>✅Your booking was successfully submitted!</h2>
                <p>We will get back to you shortly.</p>
                <button onclick='closePopup()'>OK</button>
            </div>
            <script>
                function closePopup() {
                    document.querySelector('.popup').style.display = 'none';
                    document.querySelector('.popup-overlay').style.display = 'none';
                    window.location.href = 'index.html'; // Redirect to another page
                }
            </script>
            ";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
