<?php
include './../db.php';

// Check if an ID is passed and is valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer

    // Fetch the court name
    $query = "SELECT court_name FROM courts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($court_name);
    
    if (!$stmt->fetch()) {
        echo "<script>
                Swal.fire('Error!', 'Court ID not found!', 'error')
                .then(() => window.location.href = '../index.php?courtsetup=1');
              </script>";
        exit;
    }
    $stmt->close();
} else {
    echo "<script>
            Swal.fire('Invalid!', 'Invalid ID provided!', 'warning')
            .then(() => window.location.href = '../index.php?courtsetup=1');
          </script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_court_name = trim($_POST['court_name']);

    if (empty($updated_court_name)) {
        echo "<script>Swal.fire('Warning!', 'Court name cannot be empty.', 'warning');</script>";
    } else {
        $query = "UPDATE courts SET court_name = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $updated_court_name, $id);

        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire('Success!', 'Court updated successfully!', 'success')
                    .then(() => window.location.href = '../index.php?courtsetup=1');
                  </script>";
                  echo "<script>window.location.href = '../index.php?courtsetup=1';</script>";
        } else {
            echo "<script>Swal.fire('Error!', 'Error updating court: " . $stmt->error . "', 'error');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Court</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background: #f4f4f4;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 25px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 6px;
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
    </style>
</head>

<body>
    <div class="container">
        <h1 class="form-title">🏛 Edit Court</h1>
        <form action="edit_court.php?id=<?= $id ?>" method="POST" id="editForm">
            <div class="mb-3">
                <label for="court_name" class="form-label">Court Name</label>
                <input type="text" id="court_name" name="court_name" class="form-control"
                    value="<?= htmlspecialchars($court_name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">✅ Update</button>
                <button type="button" class="btn btn-danger mt-2" onclick="confirmDelete(<?= $id ?>)">🗑 Delete</button>
                <a href="../index.php?courtsetup=1" class="btn btn-secondary">🚪 Cancel</a>
            </div>
        </form>
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
                window.location.href = `delete_court.php?id=${id}`;
            }
        });
    }

    // Prevent empty submission
    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function(event) {
        const courtName = document.getElementById('court_name').value.trim();
        if (!courtName) {
            event.preventDefault();
            Swal.fire({
                title: "Warning!",
                text: "Court name cannot be empty.",
                icon: "warning"
            });
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>