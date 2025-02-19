<?php
// Include your database connection
require_once '../db.php'; // Update with your actual database connection file
// Check if a case_id is provided
if (isset($_GET['case_id'])) {
    $case_id = $_GET['case_id'];

    // Check if more details already exist for the given case_id
    $sql = "SELECT * FROM case_details WHERE case_id = ?";
    $stmt = $conn->prepare($sql); // Prepare the query using mysqli
    $stmt->bind_param("i", $case_id); // Bind parameters (i = integer)
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    
    // Handle form submission for inserting or updating more details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $description = $_POST['description'];
        $case_laws = $_POST['case_laws'];
        $total_fees = $_POST['total_fees'];

        if ($details) {
            // Update existing record
            $update_sql = "UPDATE case_details SET description = ?, case_laws = ?, total_fees = ?, updated_at = CURRENT_TIMESTAMP WHERE case_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssdi", $description, $case_laws, $total_fees, $case_id); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "More details updated successfully!";
            echo "<script>
                    Swal.fire('Success!', '$message', 'success').then(() => window.location.reload());
                  </script>";
            echo "<script>window.location.href = '../index.php?allcase=1';</script>";
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO case_details (case_id, description, case_laws, total_fees) 
                           VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("issd", $case_id, $description, $case_laws, $total_fees); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "More details added successfully!";
            echo "<script>
                    Swal.fire('Success!', '$message', 'success').then(() => window.location.reload());
                  </script>";
            echo "<script>window.location.href = '../index.php?allcase=1';</script>";
        }
    }
} else {
    $message = "Invalid Case ID!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More Details</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f8f8;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: white;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin: 10px 0 5px;
        color: #555;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    button {
        background-color: #333;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: #555;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>More Details</h2>

        <?php if (isset($message)): ?>
        <div class="<?= isset($details) ? 'message' : 'error-message' ?>">
            <?= $message ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="case_id">Case ID:</label>
                <input type="number" id="case_id" name="case_id" value="<?= htmlspecialchars($case_id) ?>" readonly>
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"
                    required><?= htmlspecialchars($details['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="case_laws">Case Laws:</label>
                <textarea id="case_laws"
                    name="case_laws"><?= htmlspecialchars($details['case_laws'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="total_fees">Total Payable Fees:</label>
                <input type="number" id="total_fees" name="total_fees"
                    value="<?= htmlspecialchars($details['total_fees'] ?? '') ?>" required>
            </div>

            <div>
                <button type="submit"><?= isset($details) ? 'Update' : 'Insert' ?> More Details</button>
            </div>
        </form>
    </div>

</body>

</html>