<?php
include './../db.php';

// Initialize an empty array to avoid "Undefined variable" errors
$note = [
    'id' => '',
    'note_date' => '',
    'note_content' => ''
];

// Check if 'id' exists in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to integer for security
    $sql = "SELECT * FROM notes WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $note = $result->fetch_assoc(); // Fetch note data
    } else {
        echo "<script>alert('Note not found!'); window.location.href = '../index.php?dailynotes=1';</script>";
        exit(); // Stop script execution
    }
}

// Handle form submission for note update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $note_date = $_POST['note_date'];
    $note_content = $_POST['note_content'];

    // Update query
    $sql = "UPDATE notes SET note_date='$note_date', note_content='$note_content' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Note updated successfully!');</script>";
        echo "<script>window.location.href = '../index.php?dailynotes=1';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
        background: Black;

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
            <h1 class="form-title">📝 Edit Note</h1>
            <form action="edit.php" method="post" id="editForm">
                <input type="hidden" name="id" value="<?= $note['id'] ?>" />

                <div class="mb-3">
                    <label for="noteDate" class="form-label">📅 Note Date</label>
                    <input type="date" class="form-control" id="noteDate" name="note_date"
                        value="<?= $note['note_date'] ?>" required />
                </div>

                <div class="mb-3">
                    <label for="noteContent" class="form-label">✍️ Note Content</label>
                    <textarea class="form-control" id="noteContent" name="note_content" rows="4"
                        required><?= $note['note_content'] ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">✅ Update Note</button>
                    <button type="button" class="btn btn-danger mt-2" onclick="confirmDelete(<?= $note['id'] ?>)">🗑
                        Delete Note</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // SweetAlert2 Delete Confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This note will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `./delete.php?id=${id}`;
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
                text: "Note content cannot be empty.",
                icon: "warning"
            });
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>