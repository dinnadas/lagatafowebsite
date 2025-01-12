<?php
// Include the config file for the database connection
include('config.php');

// Check if the ID is passed in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Fetch the post from the database
    $postSql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($postSql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $postResult = $stmt->get_result();
    $post = $postResult->fetch_assoc();

    if (!$post) {
        // If no post found with the given ID, redirect to the admin panel
        header('Location: admin_panel.php');
        exit;
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        // Update the post in the database
        $updateSql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssi", $title, $content, $postId);
        if ($updateStmt->execute()) {
            // Redirect to the posts list if the update was successful
            header('Location: admin_panel.php');
            exit;
        } else {
            echo "Error updating post: " . $conn->error;
        }
    }
} else {
    // If no ID is passed, redirect to the admin panel
    header('Location: admin_panel.php');
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Main Content -->
    <div class="main-content" style="margin-left: 250px; padding: 20px;">
        <h1>Edit Post</h1>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Post Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
