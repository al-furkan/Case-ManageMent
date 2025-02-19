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

// Fetch existing case details
if (isset($_GET['id'])) {
    $caseId = (int) $_GET['id'];
    $query = "SELECT * FROM cases WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $caseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $case = $result->fetch_assoc();
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileNo = htmlspecialchars(trim($_POST['fileNo']));
    $caseNo = htmlspecialchars(trim($_POST['caseNo']));
    $caseType = (int) $_POST['caseType'];
    $court = (int) $_POST['court'];
    $policeStation = isset($_POST['policeStation']) ? (int) $_POST['policeStation'] : null;
    $date = htmlspecialchars(trim($_POST['date']));
    $fixedFor = htmlspecialchars(trim($_POST['fixedFor']));
    $firstParty = htmlspecialchars(trim($_POST['firstParty']));
    $secondParty = htmlspecialchars(trim($_POST['secondParty']));
    $mobileNo = htmlspecialchars(trim($_POST['mobileNo']));
    $appointedBy = htmlspecialchars(trim($_POST['appointedBy']));
    $lawSection = htmlspecialchars(trim($_POST['lawSection']));
    $comments = htmlspecialchars(trim($_POST['comments']));
    $status = htmlspecialchars(trim($_POST['status']));

    $query = "UPDATE cases SET fileNo=?, caseNo=?, case_types=?, court=?, police_stations=?, date=?, fixedFor=?, firstParty=?, secondParty=?, mobileNo=?, appointedBy=?, lawSection=?, comments=?, status=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiiisssssssssi", $fileNo, $caseNo, $caseType, $court, $policeStation, $date, $fixedFor, $firstParty, $secondParty, $mobileNo, $appointedBy, $lawSection, $comments, $status, $caseId);
    
    if ($stmt->execute()) {
        header('Location: ./allCases.php');
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Case</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #000;
        color: #fff;
    }

    .form-container {
        background: #222;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
    }

    .form-control,
    .form-select {
        border-radius: 5px;
        background-color: #333;
        color: #fff;
        border: 1px solid #fff;
    }

    .btn-primary {
        background-color: #fff;
        color: #000;
        border-radius: 5px;
    }

    .btn-primary:hover {
        background-color: #ccc;
        color: #000;
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-container">
                    <h1 class="text-center">Update Case</h1>
                    <form id="caseForm" action="" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fileNo" class="form-label">File No</label>
                                <input type="text" class="form-control" id="fileNo" name="fileNo"
                                    value="<?= $case['fileNo']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="caseNo" class="form-label">Case No*</label>
                                <input type="text" class="form-control" id="caseNo" name="caseNo"
                                    value="<?= $case['caseNo']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="caseType" class="form-label">Case Type*</label>
                                <select class="form-select" id="caseType" name="caseType" required>
                                    <option selected disabled>--Select a Case Type--</option>
                                    <?php
                $result = $conn->query("SELECT id, name FROM case_types");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id'] == $case['case_types']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                }
                ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="court" class="form-label">Court*</label>
                                <select class="form-select" id="court" name="court" required>
                                    <option selected disabled>--Select Court--</option>
                                    <?php
                $result = $conn->query("SELECT id, court_name FROM courts");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id'] == $case['court']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['court_name'] . "</option>";
                }
                ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="policeStation" class="form-label">Police Station</label>
                                <select class="form-select" id="policeStation" name="policeStation">
                                    <option selected disabled>--Select Police Station--</option>
                                    <?php
                $result = $conn->query("SELECT id, name FROM police_stations");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id'] == $case['police_stations']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                }
                ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="date" class="form-label">📅 Date:</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="<?= $case['date']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fixedFor" class="form-label">Fixed For</label>
                                <input type="text" class="form-control" id="fixedFor" name="fixedFor"
                                    value="<?= $case['fixedFor']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="firstParty" class="form-label">First Party</label>
                                <input type="text" class="form-control" id="firstParty" name="firstParty"
                                    value="<?= $case['firstParty']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="secondParty" class="form-label">Second Party</label>
                                <input type="text" class="form-control" id="secondParty" name="secondParty"
                                    value="<?= $case['secondParty']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="mobileNo" class="form-label">Mobile No</label>
                                <input type="text" class="form-control" id="mobileNo" name="mobileNo"
                                    value="<?= $case['mobileNo']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="appointedBy" class="form-label">Appointed By</label>
                                <input type="text" class="form-control" id="appointedBy" name="appointedBy"
                                    value="<?= $case['appointedBy']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="lawSection" class="form-label">Law Section</label>
                                <input type="text" class="form-control" id="lawSection" name="lawSection"
                                    value="<?= $case['lawSection']; ?>">
                            </div>
                            <div class="col-md-12">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="comments" name="comments"
                                    rows="3"><?= $case['comments']; ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status*</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Running" <?= $case['status'] == 'Running' ? 'selected' : '' ?>>
                                        Running</option>
                                    <option value="Decided" <?= $case['status'] == 'Decided' ? 'selected' : '' ?>>
                                        Decided</option>
                                    <option value="Abandoned" <?= $case['status'] == 'Abandoned' ? 'selected' : '' ?>>
                                        Abandoned</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById("caseForm").addEventListener("submit", function(event) {
        let caseNo = document.getElementById("caseNo").value.trim();
        let fileNo = document.getElementById("fileNo").value.trim();
        if (caseNo === "" || fileNo === "") {
            alert("Case No and File No are required!");
            event.preventDefault();
        }
    });
    </script>
</body>

</html>