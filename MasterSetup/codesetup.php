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
    $court_name = trim($_POST['court_name']);

    if (!empty($court_name)) {
        $query = "INSERT INTO courts (court_name) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $court_name);

        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire('Success!', 'Court added successfully!', 'success')
                    .then(() => window.location.href = './codesetup.php');
                  </script>";
                  echo "<script>window.location.href = '../index.php?courtsetup=1';</script>";
        } else {
            echo "<script>Swal.fire('Error!', 'Failed to add court!', 'error');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>Swal.fire('Warning!', 'Court name cannot be empty!', 'warning');</script>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 700px;
        margin-top: 50px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-danger {
        background: #ff4b5c;
        border: none;
    }

    .btn-danger:hover {
        background: #d63345;
    }

    .table thead th {
        background: #007bff;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center text-white">🏛 Court Management</h1>
        <p class="text-center text-white">Add and manage courts efficiently.</p>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">➕ Add Court</h5>
                <form action="./MasterSetup/codesetup.php" method="POST" class="d-flex">
                    <input type="text" name="court_name" class="form-control me-2" placeholder="Enter court name"
                        required>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">🏦 Court List</div>
            <div class="card-body p-0">
                <table class="table table-bordered text-center mb-0">
                    <thead>
                        <tr>
                            <th>Court Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['court_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href="./MasterSetup/edit_court.php?id=<?= $row['id']; ?>"
                                    class="btn btn-sm btn-success">
                                    ✏ Edit
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['id']; ?>)">
                                    🗑 Delete
                                </button>
                            </td>
                        </tr>
                        <?php } } else { ?>
                        <tr>
                            <td colspan='3' class='text-center text-muted'>No courts found</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This court will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `./MasterSetup/courtdelete.php?id=${id}`;
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>