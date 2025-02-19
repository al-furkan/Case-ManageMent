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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note_date = $_POST['note_date'];
    $note_content = $_POST['note_content'];

    $sql = "INSERT INTO notes (note_date, note_content) VALUES ('$note_date', '$note_content')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Note added successfully!',
                icon: 'success'
            }).then(() => {
                window.location.href = '../index.php?dailynotes=1';
            });
        </script>";
        echo "<script>window.location.href = '../index.php?dailynotes=1';</script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Database error: " . $conn->error . "',
                icon: 'error'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
        background: linear-gradient(to right, #dde1e7, #f8f9fa);
        font-family: 'Poppins', sans-serif;
    }

    .container {
        margin-top: 50px;
        max-width: 700px;
    }

    .card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: 0.3s ease;
    }

    .card:hover {
        box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
    }

    table {
        margin-top: 20px;
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
        <div class="card">
            <div class="text-center mb-4">
                <h2 style="color: #333;">📒 Daily Notes</h2>
                <p style="color: #666;">Stay organized and keep track of your daily tasks</p>
            </div>

            <!-- Form -->
            <form id="noteForm" action="./note/note.php" method="post">
                <div class="form-group">
                    <label for="note_date">📅 Date:</label>
                    <input type="date" name="note_date" id="note_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="note_content">📝 Note:</label>
                    <textarea class="form-control" name="note_content" id="note_content" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">➕ Add Note</button>
            </form>

            <!-- Table -->
            <table class="table table-bordered mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Task</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $sql = "SELECT * FROM notes";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['note_date']}</td>
                            <td>{$row['note_content']}</td>
                            <td>
                                <a href='./note/edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>✏ Edit</a>
                                <button class='btn btn-sm btn-danger' onclick='confirmDelete({$row['id']})'>🗑 Delete</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center text-muted'>No notes found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    // Confirm delete function
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `./note/delete.php?id=${id}`;
            }
        });
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>