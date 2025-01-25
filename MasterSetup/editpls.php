<?php
include('./../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['station_name'];
    $sql = "UPDATE police_stations SET name='$name' WHERE id=$id";
    $conn->query($sql);
    header("Location: ./police.php"); // Redirect to main page
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM police_stations WHERE id=$id");
$station = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Police Station</title>
</head>
<body>
<div class="container">
    <h2>Edit Police Station</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $station['id']; ?>">
        <div class="form-group">
            <label for="station_name">Police Station Name:</label>
            <input type="text" class="form-control" id="station_name" name="station_name" value="<?php echo $station['name']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Police Station</button>
    </form>
</div>
</body>
</html>