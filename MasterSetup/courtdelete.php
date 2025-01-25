<?php
include './../db.php';

// Check if an ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM courts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Court deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Redirect to the main page
    echo "<script>window.open('./codesetup.php', '_self');</script>";
    $stmt->close();
} else {
    echo "<script>alert('Invalid ID!');</script>";
    echo "<script>window.open('index.php', '_self');</script>";
}
?>
