<?php
include('./../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    // Handle image upload
    $image = $_FILES['image'];
    $uploadOk = 1;
    $targetDir = "uploads/"; // Directory where images will be saved
    $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    $targetFile = $targetDir . time() . '_' . basename($image['name']); // Create a unique name for the image

    // Check if an image was uploaded
    if ($image['size'] > 0) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($image['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 5MB)
        if ($image['size'] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                // Update user data into the database with new image
                $sql = "UPDATE users SET Name='$name', Image='$targetFile', email='$email', status='$status' WHERE id=$id";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // If no new image is uploaded, keep the old image
        $sql = "UPDATE users SET Name='$name', email='$email', status='$status' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Update User</title>
    <style>
        #imagePreview {
            display: none;
            max-width: 200px; /* Set a max width for the preview */
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Update User</h2>
    <form method="POST" action="./update.php?id=<?php echo $user['id']; ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['Name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Image Upload:</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            <img id="imagePreview" src="<?php echo $user['Image']; ?>" alt="Current Image" style="display: block; margin-top: 10px; max-width: 200px;">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control " id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block'; // Show the image preview
        }

        if (file) {
            reader.readAsDataURL(file); // Convert the file to a data URL
        } else {
            imagePreview.src = '<?php echo $user['Image']; ?>'; // Reset to current image
            imagePreview.style.display = 'block'; // Show the current image
        }
    }
</script>
</body>
</html>