<?php
include('../db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch the existing post
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        die("Post not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = intval($_POST['category_id']);

    // Handle image upload if a new image is provided
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $image = $post['image']; // Keep the old image
    }

    // Update the post
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $content, $image, $category_id, $id);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error updating post.";
    }
}

// Fetch categories
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Post</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control"
                    value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control"
                    required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <?php while ($category = $category_result->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>"
                        <?php echo ($category['id'] == $post['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Current Image</label><br>
                <img src="uploads/<?php echo $post['image']; ?>" width="150"><br>
                <input type="file" name="image">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="view.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>