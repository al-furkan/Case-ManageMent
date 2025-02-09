<?php
include('./../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $sql = ""; // Initialize SQL

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $targetDir = "uploads/"; // Image upload directory
        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir . time() . '_' . basename($image['name']);

        // Validate image
        if ($image['size'] > 5000000) {
            die("<script>alert('File is too large (Max 5MB).'); window.history.back();</script>");
        }
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            die("<script>alert('Invalid image format (JPG, PNG, JPEG, GIF allowed).'); window.history.back();</script>");
        }

        // Upload image
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $sql = "UPDATE users SET Name='$name', Image='$targetFile', email='$email', status='$status' WHERE id=$id";
        } else {
            die("<script>alert('File upload failed.'); window.history.back();</script>");
        }
    } else {
        // If no image uploaded, update other fields only
        $sql = "UPDATE users SET Name='$name', email='$email', status='$status' WHERE id=$id";
    }

    // Execute SQL query if it's set
    if (!empty($sql) && $conn->query($sql) === TRUE) {
        echo "<script>alert('User updated successfully!'); window.location.href = 'view.php';</script>";
    } else {
        die("<script>alert('Database error: " . $conn->error . "'); window.history.back();</script>");
    }
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update User</title>
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
        max-width: 600px;
        margin: 40px auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn {
        width: 100%;
    }

    #imagePreview {
        display: block;
        max-width: 150px;
        height: auto;
        border-radius: 10px;
        margin: 10px auto;
    }

    @media (max-width: 768px) {
        .container {
            width: 95%;
            padding: 15px;
        }

        .btn {
            font-size: 14px;
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>Update User</h2>
        <form method="POST" action="./update.php?id=<?php echo $user['id']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['Name']; ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image Upload:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*"
                    onchange="previewImage(event)">
                <img id="imagePreview" src="<?php echo $user['Image']; ?>" alt="Current Image">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active
                    </option>
                    <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-danger mt-2"
                onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
        </form>
    </div>

    <script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            imagePreview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '<?php echo $user['Image']; ?>';
        }
    }

    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = `delete.php?id=${userId}`;
        }
    }
    </script>

</body>

</html>