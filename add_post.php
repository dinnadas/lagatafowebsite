<?php
include('config.php'); // Include your database connection file

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Image upload logic
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageError = $_FILES['image']['error'];

    // Check if the image has any errors
    if ($imageError === 0) {
        // Check file size (limit: 5MB)
        if ($imageSize < 5000000) {
            // Generate a unique name for the image
            $imageNewName = uniqid('', true) . '.' . pathinfo($image, PATHINFO_EXTENSION);
            $imageDestination = 'uploads/' . $imageNewName;

            // Move the uploaded image to the "uploads" directory
            move_uploaded_file($imageTmp, $imageDestination);

            // Insert the post data into the database
            $sql = "INSERT INTO posts (title, content, image) VALUES ('$title', '$content', '$imageNewName')";
            if (mysqli_query($conn, $sql)) {
                echo "<div class='alert alert-success'>Post added successfully!</div>";
                sleep(3);
                header('Location: post.php');
            } else {
                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>File size is too large. Maximum size is 5MB.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>There was an error uploading the image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            padding: 50px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add New Post</h2>
        <form action="add_post.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" name="image" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional for form validations, modal etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
