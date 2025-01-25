<?php
include './../db.php';

// Check if an ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current court name
    $query = "SELECT court_name FROM courts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($court_name);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<script>alert('Invalid ID!');</script>";
    echo "<script>window.open('index.php', '_self');</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_court_name = $_POST['court_name'];

    // Update the court name
    $query = "UPDATE courts SET court_name = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $updated_court_name, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Court updated successfully!');</script>";
        echo "<script>window.open('./codesetup.php', '_self');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Court</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Court</h1>
    <form action="./edit_court.php" method="POST">
        <div class="mb-3">
            <label for="court_name" class="form-label">Court Name</label>
            <input type="text" id="court_name" name="court_name" class="form-control" value="<?php echo $court_name; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="./../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
