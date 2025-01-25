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

// Handle Add Case Type
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['case_type_name'];
    $sql = "INSERT INTO case_types (name) VALUES ('$name')";
    $conn->query($sql);
}

// Handle Delete Case Type
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM case_types WHERE id=$id";
    $conn->query($sql);
}

// Handle Update Case Type
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['case_type_name'];
    $sql = "UPDATE case_types SET name='$name' WHERE id=$id";
    $conn->query($sql);
}

// Fetch all case types
$result = $conn->query("SELECT * FROM case_types");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Case Type Management</title>
</head>
<body>
<div class="container">
    <h2>Case Type Management</h2>
    <form method="POST" action="./MasterSetup/case_type.php">
        <div class="form-group">
            <label for="case_type_name">Case Type Name:</label>
            <input type="text" class="form-control" id="case_type_name" name="case_type_name" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Case Type</button>
    </form>

    <h3>All Case Types</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>