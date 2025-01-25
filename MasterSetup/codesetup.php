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
// Handle form submission to add a court
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $court_name = $_POST['court_name'];

    if (!empty($court_name)) {
        $query = "INSERT INTO courts (court_name) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $court_name);

        if ($stmt->execute()) {
            echo "<script>alert('Court added successfully!');</script>";
            echo "<script>window.open('./codesetup.php', '_self');</script>";

        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Fetch all courts from the database
$query = "SELECT * FROM courts ORDER BY court_name ASC";
$result = $conn->query($query);
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        .card {
            border-radius: 8px;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .btn-success {
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Court <small class="text-muted">Add all courts</small></h1>
        <div class="card mb-4">
            <div class="card-body">
                <form action="./MasterSetup/codesetup.php" method="POST" class="d-flex">
                    <input type="text" name="court_name" class="form-control me-2" placeholder="Enter court name here" required>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">All Court Names</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Court Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['court_name']}</td>
                                <td class='text-center'>
                                    <a href='edit_court.php?id={$row['id']}' class='btn btn-sm btn-success'>
                                        <i class='bi bi-pencil'></i> Edit
                                    </a>
                                </td>
                                <td class='text-center'>
                                    <a href='courtdelete.php?id={$row['id']}' 
                                       class='btn btn-sm btn-danger' 
                                       onclick='return confirm(\"Are you sure you want to delete this court?\")'>
                                        <i class='bi bi-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No courts found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>