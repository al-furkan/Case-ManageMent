<?php
include('../db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the post to get the image filename
    $stmt = $conn->prepare("SELECT image FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
        $image = $post['image'];

        // Delete the post
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete the image file if it exists
            if (file_exists("uploads/" . $image)) {
                unlink("uploads/" . $image);
            }

            header("Location: view.php");
            exit();
        } else {
            echo "Error deleting post.";
        }
    } else {
        echo "Post not found.";
    }
} else {
    echo "Invalid request.";
}
?>