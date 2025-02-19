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

// Fetch case details
$sql = "SELECT * FROM cases WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Case not found.");
}

$case = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

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
    </style>
</head>

<body>
    <div class="container">
        <a href="allCases.php" class="btn btn-back mb-3"><i class="fas fa-arrow-left"></i> Back to Cases</a>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-balance-scale"></i> Case Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-white">
                    <tr>
                        <th>File No:</th>
                        <td><?php echo htmlspecialchars($case['fileNo']); ?></td>
                    </tr>
                    <tr>
                        <th>Case Type:</th>
                        <td><?php echo htmlspecialchars($case['case_types']); ?></td>
                    </tr>
                    <tr>
                        <th>Case No:</th>
                        <td><?php echo htmlspecialchars($case['caseNo']); ?></td>
                    </tr>
                    <tr>
                        <th>Court:</th>
                        <td><?php echo htmlspecialchars($case['court']); ?></td>
                    </tr>
                    <tr>
                        <th>Police Station:</th>
                        <td><?php echo htmlspecialchars($case['police_stations']); ?></td>
                    </tr>
                    <tr>
                        <th>Law & Section:</th>
                        <td><?php echo htmlspecialchars($case['lawSection']); ?></td>
                    </tr>
                    <tr>
                        <th>Previous Date:</th>
                        <td><?php echo htmlspecialchars($case['date']); ?></td>
                    </tr>
                    <tr>
                        <th>Next Date:</th>
                        <td><?php echo htmlspecialchars($case['date']); ?></td>
                    </tr>
                    <tr>
                        <th>Fixed For:</th>
                        <td><?php echo htmlspecialchars($case['fixedFor']); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="badge badge-success"><?php echo htmlspecialchars($case['status']); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>