<?php
include("./../db.php");

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
header("Location: ../index.php?dailynotes=1");
exit();
?>