<?php
include('../db.php'); // Database connection file

// Insert Category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            $success_message = "Category added successfully!";
        } else {
            $error_message = "Error adding category.";
        }

        $stmt->close();
    } else {
        $error_message = "Category name cannot be empty.";
    }
}

// Fetch all categories
$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = $conn->query($sql);

// Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM categories WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        header("Location: insert_catagori.php"); // Refresh page after deletion
        exit();
    } else {
        $error_message = "Error deleting category.";
    }

    $delete_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #000;
        color: #fff;
    }

    .container {
        margin-top: 50px;
    }

    .form-control,
    .btn {
        border-radius: 5px;
    }

    .table {
        background-color: #222;
        color: #fff;
    }

    .table th {
        background-color: #444;
    }

    .btn-danger {
        background-color: #ff0000;
        border: none;
    }

    .btn-danger:hover {
        background-color: #cc0000;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Category Management</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>
        <?php if (isset($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>

        <!-- Add Category Form -->
        <form method="POST" action="./insert_catagori.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="category_name" class="form-control" placeholder="Enter category name" required>
                <button type="submit" class="btn btn-light">Add Category</button>
            </div>
        </form>

        <!-- Categories Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="./insert_catagori.php?delete=<?= $row['id'] ?>"
                            class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>