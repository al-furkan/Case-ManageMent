<?php
include('./../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        die("Invalid ID!");
    }

    $id = $_POST['id'];
    $name = $_POST['case_type_name'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE case_types SET name=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    
    if ($stmt->execute()) {
        header("Location: ../index.php?casetype=1"); // Redirect to main page
        exit();
    } else {
        echo "Error updating case type: " . $conn->error;
    }

    $stmt->close();
}

// Validate ID before querying
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID!");
}

$id = $_GET['id'];

// Use prepared statement
$sql = "SELECT * FROM case_types WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$case_type = $result->fetch_assoc();

if (!$case_type) {
    die("Case type not found!");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Case Type</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 600px;
        margin-top: 50px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    .form-control {
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .container {
            margin-top: 20px;
            padding: 15px;
        }

        h2 {
            font-size: 1.5rem;
        }
    }
    </style>
</head>

<body class="bg-secondary">
    <div class="container">
        <h2 class="text-center">✏️ Edit Case Type</h2>
        <p class="text-center text-muted">Modify case type details below.</p>

        <form method="POST" action="edit.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($case_type['id']); ?>">
            <div class="mb-3">
                <label for="case_type_name" class="form-label">Case Type Name:</label>
                <input type="text" class="form-control" id="case_type_name" name="case_type_name"
                    value="<?= htmlspecialchars($case_type['name']); ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-50 me-2">Update</button>
                <a href="../index.php?casetype=1" class="btn btn-secondary w-50">Back</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>