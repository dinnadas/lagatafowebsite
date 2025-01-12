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

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch user statistics
$totalUsersSql = "SELECT COUNT(*) AS total FROM registration";
$totalUsersResult = $conn->query($totalUsersSql);
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

$maleUsersSql = "SELECT COUNT(*) AS male FROM registration WHERE gender = 'm'";
$maleUsersResult = $conn->query($maleUsersSql);
$maleUsers = $maleUsersResult->fetch_assoc()['male'];

$femaleUsersSql = "SELECT COUNT(*) AS female FROM registration WHERE gender = 'f'";
$femaleUsersResult = $conn->query($femaleUsersSql);
$femaleUsers = $femaleUsersResult->fetch_assoc()['female'];

$otherUsersSql = "SELECT COUNT(*) AS others FROM registration WHERE gender NOT IN ('m', 'f')";
$otherUsersResult = $conn->query($otherUsersSql);
$otherUsers = $otherUsersResult->fetch_assoc()['others'];

// Fetch all users for the table
$result = $conn->query("SELECT * FROM registration");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Custom Styles */
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: 'Heebo', sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1abc9c;
        }

        .sidebar a {
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #16a085;
            text-decoration: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        .statistics {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .statistics div {
            background-color: #16a085;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 20%;
        }

        .statistics div h4 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .statistics div p {
            font-size: 18px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .user-table th, .user-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .user-table th {
            background-color: #16a085;
            color: #fff;
        }

        .user-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .search-bar {
            margin-bottom: 15px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="#dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#users"><i class="fas fa-users"></i> Users</a>
        <a href="#messages"><i class="fas fa-comments"></i> Messages</a>
        <a href="#settings"><i class="fas fa-cogs"></i> Settings</a>
        <a href="#" id="logoutLink"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard -->
        <div id="dashboard" class="dashboard">
            <h1>Dashboard</h1>
            <!-- Statistics Section -->
            <div class="statistics">
                <div>
                    <h4>Total Users</h4>
                    <p><?= $totalUsers; ?></p>
                </div>
                <div>
                    <h4>Male Users</h4>
                    <p><?= $maleUsers; ?></p>
                </div>
                <div>
                    <h4>Female Users</h4>
                    <p><?= $femaleUsers; ?></p>
                </div>
                <div>
                    <h4>Other Users</h4>
                    <p><?= $otherUsers; ?></p>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div id="users">
            <h1>Users</h1>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchBar" placeholder="Search users..." onkeyup="searchUsers()">
            </div>

            <!-- Users Table -->
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <a href="edit_user.php?id=<?= $row['id']; ?>" title="Edit"><i class="fas fa-edit text-success"></i></a>
                                    <a href="delete_user.php?id=<?= $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash text-danger"></i></a>
                                </td>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['firstName']; ?></td>
                                <td><?= $row['lastName']; ?></td>
                                <td><?= $row['gender']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td><?= $row['password']; ?></td>
                                <td><?= $row['number']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Search functionality for user table
        function searchUsers() {
            const input = document.getElementById('searchBar').value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(input));
                row.style.display = match ? '' : 'none';
            });
        }

        // Show confirmation modal on logout click
        document.getElementById('logoutLink').addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default link behavior
            // Show the modal using Bootstrap's modal method
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        });
    </script>

    <!-- Bootstrap JS (for modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
