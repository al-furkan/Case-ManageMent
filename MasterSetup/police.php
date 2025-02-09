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
    $name = trim($_POST['station_name']);
    if (!empty($name)) {
        $sql = "INSERT INTO police_stations (name) VALUES ('$name')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    Swal.fire('Success!', 'Police Station added successfully!', 'success').then(() => window.location.reload());
                  </script>";
                  echo "<script>window.location.href = '../index.php?police=1';</script>";
        }
    }
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
    <title>Police Station Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        margin-top: 50px;
        max-width: 700px;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }

    h2,
    h3 {
        text-align: center;
        color: #333;
    }

    .btn {
        width: 100%;
    }

    table {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .btn {
            margin-bottom: 5px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>🚔 Police Station Management</h2>

        <!-- Add Police Station Form -->
        <form method="POST" action="./MasterSetup/police.php" id="addStationForm">
            <div class="mb-3">
                <label for="station_name" class="form-label">🏢 Police Station Name:</label>
                <input type="text" class="form-control" id="station_name" name="station_name" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">➕ Add Police Station</button>
        </form>

        <!-- Display Police Stations -->
        <h3 class="mt-4">📋 All Police Stations</h3>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
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
                        <a href="./MasterSetup/editpls.php?id=<?php echo $row['id']; ?>" class="btn btn-warning w-50">✏️
                            Edit</a>
                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger w-50">🗑
                            Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 Delete Confirmation -->
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This police station will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './MasterSetup/police_delete.php?id=' + id;
            }
        });
    }

    // Prevent empty input submission
    document.getElementById('addStationForm').addEventListener('submit', function(event) {
        let stationName = document.getElementById('station_name').value.trim();
        if (!stationName) {
            event.preventDefault();
            Swal.fire("Warning!", "Police Station Name cannot be empty.", "warning");
        }
    });
    </script>

</body>

</html>