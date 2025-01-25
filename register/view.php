<?php
include('./../db.php');

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>View Users</title>
</head>
<body>
<div class="container">
    <h2>User List</h2>
    <table class="table">
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
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['Name']; ?></td>
                <td><img src="<?php echo $user['Image']; ?>" alt="User  Image" width="50"></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['status']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Update</a>
                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>