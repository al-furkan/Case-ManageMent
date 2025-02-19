<?php
include '..'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $fixedFor = $_POST['fixedFor'];

    if (!empty($date) && !empty($fixedFor)) {
        $sql = "UPDATE cases SET date='$date', fixedFor='$fixedFor', last_updated=NOW() WHERE id=$id";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?msg=Case date updated successfully!&type=success");
        } else {
            header("Location: index.php?msg=Error updating date!&type=danger");
        }
    } else {
        header("Location: index.php?msg=All fields are required!&type=warning");
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
                $id = $_GET['id'];
                $query = "SELECT * FROM cases WHERE id = $id";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
            ?>

                <?php if (isset($_GET['msg'])) { ?>
                <div class="alert alert-<?php echo $_GET['type']; ?> text-center">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
                <?php } ?>

                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?php echo $row['date']; ?>" required>
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fixedFor" class="form-label">Fixed For <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fixedFor" name="fixedFor"
                            value="<?php echo $row['fixedFor']; ?>" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Update</button>
                    </div>
                </form>

                <?php 
                } else {
                    echo "<div class='alert alert-danger'>Case not found!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>No ID provided!</div>";
            }
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