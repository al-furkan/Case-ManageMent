<?php
session_start();
include('../db.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$Id = $_SESSION["id"];
$get_user = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $get_user);
mysqli_stmt_bind_param($stmt, "i", $Id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row_user = mysqli_fetch_array($result);

$position = $row_user['position'];

if ($position === "admin") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!isset($_POST['case_id']) || empty($_POST['case_id']) || !isset($_FILES['file'])) {
            header("Location: update_case_date.php?msg=All fields are required!&type=warning");
            exit();
        }

        $case_id = intval($_POST['case_id']);
        $upload_dir = "uploads/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES["file"]["name"]);
        $file_path = $upload_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        $allowed_types = ["pdf", "doc", "docx", "jpg", "png"];

        if (!in_array($file_type, $allowed_types)) {
            header("Location: update_case_date.php?msg=Invalid file type! Allowed: PDF, DOC, JPG, PNG.&type=danger");
            exit();
        }

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
            $sql = "INSERT INTO file (case_id, file_path) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "is", $case_id, $file_path);

            if (mysqli_stmt_execute($stmt)) {
                $updateSql = "UPDATE cases SET last_updated = NOW() WHERE id = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "i", $case_id);

                if (mysqli_stmt_execute($updateStmt)) {
                    header("Location: show_details.php?id=$case_id&msg=File uploaded successfully!&type=success");
                } else {
                    header("Location: show_details.php?id=$case_id&msg=Error updating case!&type=danger");
                }
                mysqli_stmt_close($updateStmt);
            } else {
                header("Location: show_details.php?id=$case_id&msg=Error inserting file record!&type=danger");
            }

            mysqli_stmt_close($stmt);
        } else {
            header("Location: update_case_date.php?msg=File upload failed!&type=danger");
        }

        mysqli_close($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
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
                <h4>Upload File</h4>
            </div>
            <div class="card-body">
                <?php 
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

                <form action="add_file.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="case_id" value="<?php echo $row['id']; ?>">

                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="file" name="file" required>
                            <span class="input-group-text"><i class="fa fa-file"></i></span>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-upload"></i> Upload</button>
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