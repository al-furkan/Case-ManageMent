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
    $name = trim($_POST['case_type_name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO case_types (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire('Success!', 'Case Type Added Successfully!', 'success')
                .then(() => window.location.href = '../index.php?casetype=1');
                </script>";
                echo "<script>window.location.href = '../index.php?casetype=1';</script>";
        } else {
            echo "<script>Swal.fire('Error!', 'Error adding case type!', 'error');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>Swal.fire('Warning!', 'Case type name cannot be empty!', 'warning');</script>";
    }
}

// Fetch all case types
$result = $conn->query("SELECT * FROM case_types ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Type Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 750px;
        margin-top: 50px;
        background: white;
        padding: 20px;
        border-radius: 10px;
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

    .form-control {
        border-radius: 5px;
    }

    .table td {
        vertical-align: middle;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">⚖️ Case Type Management</h2>
        <p class="text-center text-muted">Add and manage case types efficiently.</p>

        <!-- Add Case Type Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">➕ Add Case Type</h5>
                <form action="./MasterSetup/case_type.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="case_type_name" placeholder="Enter case type name"
                            required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary mt-2">Add Case Type</button>
                </form>
            </div>
        </div>

        <!-- Case Type List -->
        <div class="card">
            <div class="card-header bg-primary text-white">📄 All Case Types</div>
            <div class="card-body p-0">
                <table class="table table-bordered text-center mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href="./MasterSetup/edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-success">
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
                            <td colspan='4' class='text-center text-muted'>No case types found</td>
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
            text: "This case type will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `./MasterSetup/case_delete.php?id=${id}`;
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>