<?php
// Database connection
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'case-management';

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = $_POST['status'];

    // Handle image upload
    $image = $_FILES['image'];
    $imageName = time() . '_' . basename($image['name']);
    $targetDir = "uploads/";
    $targetFile = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Image validation
    $check = getimagesize($image['tmp_name']);
    if ($check === false) {
        die("<script>alert('File is not an image.'); window.history.back();</script>");
    }

    if ($image['size'] > 5000000) {
        die("<script>alert('File is too large.'); window.history.back();</script>");
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        die("<script>alert('Only JPG, JPEG, PNG & GIF are allowed.'); window.history.back();</script>");
    }

    if (move_uploaded_file($image['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO users (Name, Image, email, password, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $targetFile, $email, $password, $status);

        if ($stmt->execute()) {
            echo "<script>alert('User registered successfully!'); window.location.href='../index.php?register=1';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Insert User</title>
    <style>
    body {
        background-color: #121212;
        color: #ffffff;
        padding: 20px;
    }

    .container {
        background: #1e1e1e;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1);
        max-width: 600px;
        margin: auto;
    }

    .form-control {
        background-color: #333;
        color: white;
        border: 1px solid #555;
    }

    .form-control:focus {
        background-color: #444;
        color: white;
        border-color: #007bff;
    }

    label {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        width: 100%;
        padding: 10px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    #imagePreview {
        display: none;
        max-width: 100%;
        height: auto;
        margin-top: 10px;
        border-radius: 5px;
        border: 2px solid #007bff;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 1.5rem;
            text-align: center;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-white text-center">Insert User</h2>
        <form method="POST" action="./register/register.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="image">Image Upload:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required
                    onchange="previewImage(event)">
                <img id="imagePreview" src="#" alt="Image Preview">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    }
    </script>
</body>

</html>