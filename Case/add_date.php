<?php

session_start();
include('../db.php');
if (!isset($_SESSION['id'])) {
  header("Location: ../login.php");
  exit();

  
}
$Id =  $_SESSION["id"];
$get_user = "select * from users where id ='$Id'";
$run_user = mysqli_query($conn, $get_user);
$row_user=mysqli_fetch_array($run_user);
 $id = $row_user['id'];
 $position = $row_user['position'];

  if($position=="admin"){



if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (!isset($_POST['case_id']) || empty($_POST['case_id']) || empty($_POST['date']) || empty($_POST['fixedFor'])) {
header("Location: update_case_date.php?msg=All fields are required!&type=warning");
exit();
}

$case_id = intval($_POST['case_id']); // Securely sanitize case_id
$date = trim($_POST['date']);
$fixedFor = trim($_POST['fixedFor']);

// Insert new record into add_date table
$sql = "INSERT INTO add_date (case_id, date, fixedFor) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
mysqli_stmt_bind_param($stmt, "iss", $case_id, $date, $fixedFor);

if (mysqli_stmt_execute($stmt)) {
// Now update the 'last_updated' field in the 'cases' table based on the inserted date
$updateSql = "UPDATE cases c
JOIN add_date ad ON c.id = ad.case_id
SET c.last_updated = ad.date
WHERE ad.case_id = ?";
$updateStmt = mysqli_prepare($conn, $updateSql);
mysqli_stmt_bind_param($updateStmt, "i", $case_id);

if (mysqli_stmt_execute($updateStmt)) {
header("Location: ./show_details.php?id=$case_id&msg=Case date added and updated successfully!&type=success");
} else {
header("Location: show_details.php?id=$case_id&msg=Error updating case!&type=danger");
}
mysqli_stmt_close($updateStmt);
} else {
header("Location: show_details.php?id=$case_id&msg=Error inserting case date!&type=danger");
}

mysqli_stmt_close($stmt);
} else {
header("Location: show_details.php?id=$case_id&msg=Database error!&type=danger");
}

mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Case Date</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
    /* Dark & Light Mode */
    body {
        transition: background 0.3s, color 0.3s;
    }

    .dark-mode {
        background: #121212;
        color: white;
    }

    .card {
        transition: background 0.3s, color 0.3s;
    }

    .dark-mode .card {
        background: #1e1e1e;
        color: white;
    }

    .dark-mode .form-control {
        background: #333;
        color: white;
        border: 1px solid #777;
    }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="text-end">
            <button class="btn btn-dark" onclick="toggleDarkMode()"><i class="fa fa-moon"></i> Toggle Dark Mode</button>
        </div>

        <div class="card shadow-lg mt-3">
            <div class="card-header bg-primary text-white">
                <h4>Update Case Date</h4>
            </div>
            <div class="card-body">
                <?php 
                include '../db.php';
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    $query = "SELECT * FROM cases WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                ?>

                <?php if (isset($_GET['msg'])) { ?>
                <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> text-center">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
                <?php } ?>

                <form action="./add_date.php" method="POST">
                    <input type="hidden" name="case_id" value="<?php echo $row['id']; ?>">

                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date" name="date" required>
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fixedFor" class="form-label">Fixed For <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fixedFor" name="fixedFor" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Update</button>
                    </div>
                </form>

                <?php 
                    } else {
                        echo "<div class='alert alert-danger'>Case not found!</div>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<div class='alert alert-danger'>No ID provided!</div>";
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>

    <script>
    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }
    </script>

</body>

</html>

<?php } ?>