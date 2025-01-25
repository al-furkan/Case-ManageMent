<?php
include './../db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM notes WHERE id = $id";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Notes Delete successfully!');</script>";
    echo "<script>window.open('../index.php?dailynotes=1', '_self');</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}

$conn->close();
?>
