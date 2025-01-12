<?php
// Include the config file for the database connection
include('config.php');

// Check if the ID is passed in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Delete the post from the database
    $deleteSql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $postId);
    
    if ($stmt->execute()) {
        // Redirect to the admin panel after successful deletion
        header('Location: post.php');
        exit;
    } else {
        echo "Error deleting post: " . $conn->error;
    }
} else {
    // If no ID is passed, redirect to the admin panel
    header('Location: pos.php');
    exit;
}
?>
