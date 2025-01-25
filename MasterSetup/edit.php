<?php
include('./../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['case_type_name'];
    $sql = "UPDATE case_types SET name='$name' WHERE id=$id";
    $conn->query($sql);
    header("Location: ./case_type.php"); // Redirect to main page
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM case_types WHERE id=$id");
$case_type = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Case Type</title>
    <style>
    body {
        padding: 20px;
    }
    @media (max-width: 768px) {
        .container {
            padding: 0;
        }
        h2, h3 {
            font-size: 1.5rem;
        }
        .form-group {
            margin-bottom: 15px;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h2>Edit Case Type</h2>
    <form method="POST" action="./edit.php">
        <input type="hidden" name="id" value="<?php echo $case_type['id']; ?>">
        <div class="form-group">
            <label for="case_type_name">Case Type Name:</label>
            <input type="text" class="form-control" id="case_type_name" name="case_type_name" value ="<?php echo $case_type['name']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Case Type</button>
    </form>
</div>

</body>
</html>
