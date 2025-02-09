<?php
include('./../db.php');

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Users</title>
    <style>
    body {
        background-color: #121212;
        color: #fff;
    }

    .container {
        background: #1e1e1e;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        max-width: 90%;
        margin: 20px auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
        background: #2a2a2a;
    }

    .table thead {
        background: #007bff;
        color: white;
    }

    .table tbody tr {
        transition: all 0.3s ease-in-out;
    }

    .table tbody tr:hover {
        background: #333;
    }

    .table img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #007bff;
    }

    .btn {
        padding: 5px 10px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .container {
            width: 100%;
            padding: 10px;
        }

        .table th,
        .table td {
            font-size: 14px;
        }

        .btn {
            padding: 3px 7px;
            font-size: 12px;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>User List</h2>

        <!-- Success and Error Messages -->
        <div id="message" class="alert d-none"></div>

        <div class="table-responsive">
            <table class="table table-striped table-dark table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                    <tr id="row-<?php echo $user['id']; ?>">
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['Name']; ?></td>
                        <td><img src="<?php echo $user['Image']; ?>" alt="User Image"></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($user['status'] == 'active') ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="update.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                            <button onclick="confirmDelete(<?php echo $user['id']; ?>)"
                                class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function showMessage(type, text) {
        const messageDiv = document.getElementById('message');
        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = text;
        messageDiv.classList.remove('d-none');
        setTimeout(() => {
            messageDiv.classList.add('d-none');
        }, 3000);
    }

    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            fetch(`delete.php?id=${userId}`)
                .then(response => response.text())
                .then(data => {
                    if (data.includes('success')) {
                        showMessage('success', 'User deleted successfully.');
                        document.getElementById(`row-${userId}`).remove();
                    } else {
                        showMessage('danger', 'Error deleting user.');
                    }
                })
                .catch(error => {
                    showMessage('danger', 'Error connecting to the server.');
                });
        }
    }
    </script>

</body>

</html>