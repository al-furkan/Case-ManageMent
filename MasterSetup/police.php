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

// Handle Add Police Station
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['station_name'];
    $sql = "INSERT INTO police_stations (name) VALUES ('$name')";
    $conn->query($sql);
}

// Handle Delete Police Station
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM police_stations WHERE id=$id";
    $conn->query($sql);
}

// Handle Update Police Station
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['station_name'];
    $sql = "UPDATE police_stations SET name='$name' WHERE id=$id";
    $conn->query($sql);
}

// Fetch all police stations
$result = $conn->query("SELECT * FROM police_stations");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Police Station Management</title>
</head>
<body>
<div class="container">
    <h2>Police Station Management</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="station_name">Police Station Name:</label>
            <input type="text" class="form-control" id="station_name" name="station_name" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Police Station</button>
    </form>

    <h3>All Police Stations</h3>
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
                        <a href="editpls.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>