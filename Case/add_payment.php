<?php
// Include your database connection
require_once '../db.php'; // Update with your actual database connection file

// Check if a case_id is provided
if (isset($_GET['case_id'])) {
    $case_id = $_GET['case_id'];

    // Check if payment details already exist for the given case_id
    $sql = "SELECT * FROM payments WHERE case_id = ?";
    $stmt = $conn->prepare($sql); // Prepare the query using mysqli
    $stmt->bind_param("i", $case_id); // Bind parameters (i = integer)
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();
    
    // Handle form submission for inserting or updating payment details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $payment_type = $_POST['payment_type'];
        $amount = $_POST['amount'];
        $payment_date = $_POST['payment_date'];

        if ($payment) {
            // Update existing payment record
            $update_sql = "UPDATE payments SET name = ?, payment_type = ?, amount = ?, payment_date = ?, updated_at = CURRENT_TIMESTAMP WHERE case_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssdsi", $name, $payment_type, $amount, $payment_date, $case_id); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "Payment details updated successfully!";
            echo "<script>
                    Swal.fire('Success!', '$message', 'success').then(() => window.location.reload());
                  </script>";
            echo "<script>window.location.href = '../index.php?allcase=1';</script>";
        } else {
            // Insert new payment record
            $insert_sql = "INSERT INTO payments (case_id, name, payment_type, amount, payment_date) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("issds", $case_id, $name, $payment_type, $amount, $payment_date); // Bind parameters
            $stmt->execute();

            // Success Message
            $message = "Payment details added successfully!";
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
    <title>Payment Details</title>
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
        <h2>Payment Details</h2>

        <?php if (isset($message)): ?>
        <div class="<?= isset($payment) ? 'message' : 'error-message' ?>">
            <?= $message ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="case_id">Case ID:</label>
                <input type="number" id="case_id" name="case_id" value="<?= htmlspecialchars($case_id) ?>" readonly>
            </div>

            <div>
                <label for="name">Payer Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($payment['name'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="payment_type">Payment Type:</label>
                <select id="payment_type" name="payment_type" required>
                    <option value="Cash"
                        <?= (isset($payment) && $payment['payment_type'] == 'Cash') ? 'selected' : '' ?>>Cash</option>
                    <option value="Credit Card"
                        <?= (isset($payment) && $payment['payment_type'] == 'Credit Card') ? 'selected' : '' ?>>Credit
                        Card</option>
                    <option value="Bank Transfer"
                        <?= (isset($payment) && $payment['payment_type'] == 'Bank Transfer') ? 'selected' : '' ?>>Bank
                        Transfer</option>
                </select>
            </div>

            <div>
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($payment['amount'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="payment_date">Payment Date:</label>
                <input type="date" id="payment_date" name="payment_date"
                    value="<?= htmlspecialchars($payment['payment_date'] ?? '') ?>" required>
            </div>

            <div>
                <button type="submit"><?= isset($payment) ? 'Update' : 'Insert' ?> Payment Details</button>
            </div>
        </form>
    </div>

</body>

</html>