<?php
// Database credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'case-management';

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if case ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid case ID.");
}

$case_id = intval($_GET['id']); // Sanitize input

// Function to fetch data securely
function fetchData($conn, $sql, $param) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $param);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch case details
$case_result = fetchData($conn, "SELECT * FROM cases WHERE id = ?", $case_id);
$case = $case_result->fetch_assoc();
if (!$case) {
    die("Case not found.");
}

// Fetch related details
$add_date_result = fetchData($conn, "SELECT * FROM add_date WHERE case_id = ? ORDER BY last_updated DESC", $case_id);
$party_result = fetchData($conn, "SELECT * FROM party_details WHERE case_id = ?", $case_id);
$case_details_result = fetchData($conn, "SELECT * FROM case_details WHERE case_id = ?", $case_id);
$payment_result = fetchData($conn, "SELECT * FROM payments WHERE case_id = ?", $case_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
    body {
        background-color: #1c1c1c;
        color: white;
    }

    .container {
        margin-top: 40px;
    }

    .card {
        background-color: #2c2c2c;
        border: 1px solid #444;
    }

    .card-header {
        background-color: #444;
        color: white;
    }

    .btn-back {
        background-color: #000;
        color: white;
        border: 1px solid white;
    }

    .btn-back:hover {
        background-color: white;
        color: black;
    }

    .badge-success {
        background-color: green;
        padding: 5px;
        border-radius: 5px;
    }

    .table-bordered>:not(caption)>*>* {
        background: #333;
        color: #fff;
        border-width: 0 var(--bs-border-width);
    }
    </style>
</head>

<body>
    <div class="container">
        <a href="../index.php?allcase=1" class="btn btn-back mb-3"><i class="fas fa-arrow-left"></i> Back to Cases</a>

        <!-- Case Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-balance-scale"></i> Case Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <tr>
                        <th>File No:</th>
                        <td><?= htmlspecialchars($case['fileNo']) ?></td>
                    </tr>
                    <tr>
                        <th>Case No:</th>
                        <td><?= htmlspecialchars($case['caseNo']) ?></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><?= htmlspecialchars($case['case_types']) ?></td>
                    </tr>
                    <tr>
                        <th>Court:</th>
                        <td><?= htmlspecialchars($case['court']) ?></td>
                    </tr>
                    <tr>
                        <th>Police Station:</th>
                        <td><?= htmlspecialchars($case['police_stations']) ?></td>
                    </tr>
                    <tr>
                        <th>Law & Section:</th>
                        <td><?= htmlspecialchars($case['lawSection']) ?></td>
                    </tr>
                    <tr>
                        <th>Fixed For:</th>
                        <td><?= htmlspecialchars($case['fixedFor']) ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="badge badge-success"><?= htmlspecialchars($case['status']) ?></span></td>
                    </tr>
                    <tr>
                        <th>Hearing Date:</th>
                        <td><?= htmlspecialchars($case['hearing_date']) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Date Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-calendar"></i> Date Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <thead>
                        <tr>
                            <th>Sr_No</th>
                            <th>Date</th>
                            <th>Fixed For</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  $i=0;
                        while ($row = $add_date_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['fixedFor'] ?></td>
                            <td><?= $row['last_updated'] ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Party Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-users"></i> Party Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Type</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($party = $party_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($party['name']) ?></td>
                            <td><?= htmlspecialchars($party['email']) ?></td>
                            <td><?= htmlspecialchars($party['mobile_no']) ?></td>
                            <td><?= htmlspecialchars($party['party_type']) ?></td>
                            <td><?= htmlspecialchars($party['address']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Case Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-file-alt"></i> Additional Case Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Case Laws</th>
                            <th>Total Fees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($case_detail = $case_details_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($case_detail['description']) ?></td>
                            <td><?= htmlspecialchars($case_detail['case_laws']) ?></td>
                            <td><?= htmlspecialchars($case_detail['total_fees']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-money-bill"></i> Payment Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($payment = $payment_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                            <td><?= htmlspecialchars($payment['name']) ?></td>
                            <td><?= htmlspecialchars($payment['payment_type']) ?></td>
                            <td><?= htmlspecialchars($payment['amount']) ?></td>
                            <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>