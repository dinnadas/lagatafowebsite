<?php
session_start();

$error_message = ''; // Initialize an empty variable to hold error messages

if (isset($_SESSION['logged_in'])) {
    // If already logged in, redirect to admin panel
    header("Location: admin_panel.php");
    exit;
}

if (isset($_POST['login'])) {
    // Retrieve login credentials from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'lagatafo');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if the admin exists in the database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin found, verify password (no hashing)
        $admin = $result->fetch_assoc();
        if ($password == $admin['password']) { // No hashing, direct comparison
            // Password is correct, login successful
            $_SESSION['logged_in'] = true;
            header("Location: admin_panel.php");
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e7c99, #4e5b6e);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 5px rgba(0, 188, 212, 0.5);
        }

        .btn-login {
            width: 100%;
            background-color: #00bcd4;
            border: none;
            padding: 14px;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #00a2b8;
            transform: scale(1.05);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .text-center {
            margin-top: 15px;
            color: #333;
        }

        .text-center a {
            text-decoration: none;
            color: #00bcd4;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .container {
            text-align: center;
            padding: 50px;
        }

        .btn-start {
            background-color: #4caf50;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-start:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
        </form>

        <div class="text-center">
            <p>Forgot your password? <a href="#">Reset it here</a></p>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
