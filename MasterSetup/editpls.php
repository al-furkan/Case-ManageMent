<?php
include './../db.php';

// Initialize an empty array to avoid "Undefined variable" errors
$note = [
    'id' => '',
    'name' => ''
];

// Check if 'id' exists in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to integer for security

    $stmt = $conn->prepare("SELECT * FROM police_stations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $note = $result->fetch_assoc();
    } else {
        echo "<script>alert('Police Station not found!'); window.location.href = '../index.php?police=1';</script>";
        exit();
    }
    $stmt->close();
}

// Handle form submission for updating police station
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo "<script>alert('Invalid Police Station ID'); window.location.href = '../index.php?police=1';</script>";
        exit();
    }

    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['note_content']);

    // Update query with prepared statement
    $stmt = $conn->prepare("UPDATE police_stations SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire('Success!', 'Police Station updated successfully!', 'success').then(() => window.location.href = '../index.php?police=1');
              </script>";
              echo "<script>window.location.href = '../index.php?police=1';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Police Station</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
        background: black;
        font-family: 'Poppins', sans-serif;
    }

    .form-container {
        max-width: 500px;
        margin: 60px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transition: 0.3s ease;
    }

    .form-container:hover {
        box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.15);
    }

    .form-title {
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 6px;
    }

    .btn-primary {
        background: #007bff;
        border: none;
        transition: 0.3s ease;
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
        <div class="form-container">
            <h1 class="form-title">📝 Edit Police Station</h1>
            <form action="./editpls.php" method="post" id="editForm">
                <input type="hidden" name="id" value="<?= htmlspecialchars($note['id']) ?>" />

                <div class="mb-3">
                    <label for="noteContent" class="form-label">✍️ Edit Police Name</label>
                    <textarea class="form-control" id="noteContent" name="note_content" rows="4"
                        required><?= htmlspecialchars($note['name']) ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">✅ Update</button>
                    <button type="button" class="btn btn-danger mt-2"
                        onclick="confirmDelete(<?= htmlspecialchars($note['id']) ?>)">🗑 Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // SweetAlert2 Delete Confirmation
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
                window.location.href = `./police_delete.php?id=${id}`;
            }
        });
    }

    // Prevent empty content submission
    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function(event) {
        const noteContent = document.getElementById('noteContent').value.trim();
        if (!noteContent) {
            event.preventDefault();
            Swal.fire({
                title: "Warning!",
                text: "Police Station Name cannot be empty.",
                icon: "warning"
            });
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>