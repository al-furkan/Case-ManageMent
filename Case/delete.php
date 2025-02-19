<?php
// Database credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'case-management';

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['id'])) {
    $caseId = (int) $_GET['id'];
    $query = "DELETE FROM cases WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $caseId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Case deleted successfully!'); window.location.href='./allCases.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Case</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #000;
        color: #fff;
    }

    .container {
        text-align: center;
        margin-top: 50px;
    }

    .btn {
        margin: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Are you sure you want to delete this case?</h2>
        <a href="delete.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger">Yes, Delete</a>
        <a href="allCases.php" class="btn btn-secondary">Cancel</a>
    </div>
</body>

</html>