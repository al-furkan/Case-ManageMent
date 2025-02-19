<?php
// Include your database connection
require_once '../db.php'; // Update with your actual database connection file
// Check if a case_id is provided
if (isset($_GET['case_id'])) {
    $case_id = $_GET['case_id'];

    // Check if party details already exist for the given case_id
    $sql = "SELECT * FROM party_details WHERE case_id = ?";
    $stmt = $conn->prepare($sql); // Prepare the query using mysqli
    $stmt->bind_param("i", $case_id); // Bind parameters (i = integer)
    $stmt->execute();
    $result = $stmt->get_result();
    $party = $result->fetch_assoc();
    
    // Handle form submission for inserting or updating party details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile_no = $_POST['mobile_no'];
        $party_type = $_POST['party_type'];
        $address = $_POST['address'];

        if ($party) {
            // Update existing record
            $update_sql = "UPDATE party_details SET name = ?, email = ?, mobile_no = ?, party_type = ?, address = ?, updated_at = CURRENT_TIMESTAMP WHERE case_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sssssi", $name, $email, $mobile_no, $party_type, $address, $case_id); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "Party details updated successfully!";
            echo "<script>
                    Swal.fire('Success!', '$message', 'success').then(() => window.location.reload());
                  </script>";
            echo "<script>window.location.href = '../index.php?allcase=1';</script>";
        } else {
            // Insert new record
            $insert_sql = "INSERT INTO party_details (case_id, name, email, mobile_no, party_type, address) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("isssss", $case_id, $name, $email, $mobile_no, $party_type, $address); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "Party details added successfully!";
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
    <title>Party Details</title>
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
        <h2>Party Details</h2>

        <?php if (isset($message)): ?>
        <div class="<?= isset($party) ? 'message' : 'error-message' ?>">
            <?= $message ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="case_id">Case ID:</label>
                <input type="number" id="case_id" name="case_id" value="<?= htmlspecialchars($case_id) ?>" readonly>
            </div>

            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($party['name'] ?? '') ?>" required>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($party['email'] ?? '') ?>">
            </div>

            <div>
                <label for="mobile_no">Mobile No:</label>
                <input type="text" id="mobile_no" name="mobile_no"
                    value="<?= htmlspecialchars($party['mobile_no'] ?? '') ?>" required>
            </div>

            <div>
                <label for="party_type">Party Type:</label>
                <select id="party_type" name="party_type" required>
                    <option value="Appointed_Party"
                        <?= (isset($party) && $party['party_type'] == 'Appointed_Party') ? 'selected' : '' ?>>
                        Appointed_Party
                    </option>
                    <option value="Opposite_Advocate"
                        <?= (isset($party) && $party['party_type'] == 'Opposite_Advocate') ? 'selected' : '' ?>>
                        Opposite_Advocate
                    </option>
                    <option value="Witness"
                        <?= (isset($party) && $party['party_type'] == 'Witness') ? 'selected' : '' ?>>Witness</option>
                    <option value="Lawyer" <?= (isset($party) && $party['party_type'] == 'Lawyer') ? 'selected' : '' ?>>
                        Lawyer</option>
                </select>
            </div>

            <div>
                <label for="address">Address:</label>
                <textarea id="address" name="address"
                    required><?= htmlspecialchars($party['address'] ?? '') ?></textarea>
            </div>

            <div>
                <button type="submit"><?= isset($party) ? 'Update' : 'Insert' ?> Party Details</button>
            </div>
        </form>
    </div>

</body>

</html>