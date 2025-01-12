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

// Fetch user data
$sql = "SELECT * FROM registration WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $number = $_POST['number'];

    $update_sql = "UPDATE registration SET firstName = ?, lastName = ?, gender = ?, email = ?, password = ?, number = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $firstName, $lastName, $gender, $email, $password, $number, $id);

    if ($update_stmt->execute()) {
        header("Location: admin_panel.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <!-- Bootstrap 5 CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 2.2rem;
            color: #34495e;
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
            padding-top: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #16a085;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group label {
            position: absolute;
            top: -12px;
            left: 15px;
            font-size: 1.1rem;
            color: #7f8c8d;
            font-weight: 500;
            background-color: #fff;
            padding: 0 10px;
            transition: 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #16a085;
            box-shadow: 0 0 5px rgba(22, 160, 133, 0.3);
        }

        .form-group input:focus + label {
            color: #16a085;
        }

        .form-group i {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 1.2rem;
            color: #ccc;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            font-size: 1.2rem;
            background-color: #1abc9c;
            border: none;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #16a085;
            cursor: pointer;
        }

        .btn-primary:focus {
            outline: none;
        }

        .btn-primary:active {
            transform: translateY(2px);
        }

        .form-group input::-webkit-input-placeholder {
            color: #aaa;
        }

        .form-group input:-moz-placeholder {
            color: #aaa;
        }

        .form-group input::-moz-placeholder {
            color: #aaa;
        }

        .form-group input:-ms-input-placeholder {
            color: #aaa;
        }

        /* To fix input overflow on smaller screens */
        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }

            .form-container h2 {
                font-size: 1.8rem;
            }
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h3>Edit User</h3>
            <form method="post">
                <div class="form-group">
                    <input type="text" name="firstName" id="firstName" value="<?= $user['firstName'] ?>" required>
                    <label for="firstName">First Name</label>
                </div>

                <div class="form-group">
                    <input type="text" name="lastName" id="lastName" value="<?= $user['lastName'] ?>" required>
                    <label for="lastName">Last Name</label>
                </div>

                <div class="form-group">
                    <input type="text" name="gender" id="gender" value="<?= $user['gender'] ?>" required>
                    <label for="gender">Gender</label>
                </div>

                <div class="form-group">
                    <input type="email" name="email" id="email" value="<?= $user['email'] ?>" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-group">
                    <input type="text" name="password" id="password" value="<?= $user['password'] ?>" required>
                    <label for="password">Password</label>
                </div>

                <div class="form-group">
                    <input type="text" name="number" id="number" value="<?= $user['number'] ?>" required>
                    <label for="number">Phone Number</label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
