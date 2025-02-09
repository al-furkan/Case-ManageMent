<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Include database connection
  $conn = new mysqli('localhost', 'username', 'password', 'database_name');
  if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
  }

  // Sanitize and validate input data
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

  // Use a prepared statement to insert data
  $query = "INSERT INTO cases 
              (fileNo, caseNo, case_type, court, police_stations, date, fixedFor, firstParty, secondParty, mobileNo, appointedBy, lawSection, comments, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);

  if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
  }

  $stmt->bind_param(
    "ssiiisssssssss",
    $fileNo,
    $caseNo,
    $caseType,
    $court,
    $policeStation,
    $date,
    $fixedFor,
    $firstParty,
    $secondParty,
    $mobileNo,
    $appointedBy,
    $lawSection,
    $comments,
    $status
  );

  if ($stmt->execute()) {
    header('Location: ./allCases.php');
    exit();
  } else {
    echo "<script>alert('Error: " . $stmt->error . "');</script>";
  }

  $stmt->close();
  $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Case Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .form-title {
        font-size: 24px;
        font-weight: bold;
    }

    .form-subtitle {
        font-size: 16px;
        color: #6c757d;
    }

    .form-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-select {
        border-radius: 5px;
    }

    .btn-primary {
        border-radius: 5px;
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-container">
                    <h1 class="form-title">New Case</h1>
                    <p class="form-subtitle">Add new case</p>
                    <form id="caseForm" action="./index.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fileNo" class="form-label">File No</label>
                                <input type="text" class="form-control" id="fileNo" name="fileNo"
                                    placeholder="Enter File No">
                            </div>
                            <div class="col-md-6">
                                <label for="caseNo" class="form-label">Case No*</label>
                                <input type="text" class="form-control" id="caseNo" name="caseNo"
                                    placeholder="Enter Case No/Year" required>
                            </div>
                            <div class="col-md-6">
                                <label for="caseType" class="form-label">Case Type*</label>
                                <select class="form-select" id="caseType" name="caseType" required>
                                    <option selected disabled>--Select a Case Type--</option>
                                    <?php
                  $query = "SELECT id, name FROM case_types";
                  $result = mysqli_query($conn, $query);
                  if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                  } else {
                    echo "<option value='' disabled>Error loading case types</option>";
                  }
                  ?>
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="court" class="form-label">Court*</label>
                                <select class="form-select" id="court" name="court" required>
                                    <option selected disabled>--Select Court--</option>
                                    <?php
                  $query = "SELECT id, court_name FROM courts";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->get_result();

                  while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['court_name']) . "</option>";
                  }
                  ?>
                                </select>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="policeStation" class="form-label">Police Station</label>
                                <select class="form-select" id="policeStation" name="policeStation">
                                    <option selected disabled>--Select Police Station--</option>
                                    <?php 
                $query = "SELECT id, name FROM police_stations";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date*</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fixedFor" class="form-label">Fixed For</label>
                                <input type="text" class="form-control" id="fixedFor" name="fixedFor"
                                    placeholder="Enter date fixed for what">
                            </div>
                            <div class="col-md-6">
                                <label for="firstParty" class="form-label">1st Party</label>
                                <input type="text" class="form-control" id="firstParty" name="firstParty"
                                    placeholder="Enter First party">
                            </div>
                            <div class="col-md-6">
                                <label for="secondParty" class="form-label">2nd Party</label>
                                <input type="text" class="form-control" id="secondParty" name="secondParty"
                                    placeholder="Enter Second party">
                            </div>
                            <div class="col-md-6">
                                <label for="mobileNo" class="form-label">Mobile No</label>
                                <input type="tel" class="form-control" id="mobileNo" name="mobileNo"
                                    placeholder="Enter Client mobile no" pattern="[0-9]{10}">
                            </div>
                            <div class="col-md-6">
                                <label for="appointedBy" class="form-label">Appointed By</label>
                                <input type="text" class="form-control" id="appointedBy" name="appointedBy"
                                    placeholder="Enter which party appointed you">
                            </div>
                            <div class="col-md-6">
                                <label for="lawSection" class="form-label">Law & Section</label>
                                <input type="text" class="form-control" id="lawSection" name="lawSection"
                                    placeholder="Enter Law & Section">
                            </div>
                            <div class="col-12">
                                <label for="comments" class="form-label">Comments/Others</label>
                                <textarea class="form-control" id="comments" name="comments"
                                    placeholder="Enter more about this case" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status*</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option selected disabled>--Select Status--</option>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>