<?php
include('../db.php');

$success_message = $error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $date = date('Y-m-d'); // Current date
    $comments = 0; // Default comments count
    $category_id = intval($_POST['category_id']); // Ensure it's an integer

    // Image upload handling
    $target_dir = "uploads/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image type
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO posts (title, content, image, date, comments, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $title, $content, $image, $date, $comments, $category_id);

            if ($stmt->execute()) {
                $success_message = "Post added successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "File upload failed.";
        }
    }
}

// Fetch categories
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #000;
        color: #fff;
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 600px;
        margin-top: 50px;
        background: #222;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
    }

    .form-control {
        background-color: #333;
        color: #fff;
        border: 1px solid #555;
    }

    .form-control:focus {
        background-color: #444;
        color: #fff;
        border-color: #fff;
    }

    .btn-primary {
        background-color: #fff;
        color: #000;
        border: none;
    }

    .btn-primary:hover {
        background-color: #ddd;
        color: #000;
    }

    .alert {
        background-color: #444;
        color: #fff;
        border: 1px solid #fff;
    }

    label {
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Add New Post</h2>

        <!-- Success/Error Message -->
        <?php if (isset($success_message)) { ?>
        <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php } ?>

        <form action="insert.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="content">Post Content</label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                    <option value="">Select a Category</option>
                    <?php while ($category = $category_result->fetch_assoc()) { ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Post</button>
        </form>
    </div>

</body>

</html>