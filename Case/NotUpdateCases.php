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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cases Not Updated</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <style>
    body {
        background-color: #1c1c1c;
        color: white;
    }

    .table th,
    .table td {
        font-size: 12px;
    }

    .table {
        background-color: #2c2c2c;
    }

    .table thead th {
        background-color: #444;
        color: #fff;
    }

    .table tbody tr:hover {
        background-color: #3a3a3a;
    }

    .badge-success {
        background-color: #28a745;
    }

    .btn-custom {
        color: #fff;
        background-color: #000;
        border: 1px solid #fff;
    }

    .btn-custom:hover {
        background-color: #fff;
        color: #000;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-gavel"></i> Cases Not Updated</h1>
            <div>
                <button class="btn btn-dark btn-sm" id="pdf-visible"><i class="fas fa-file-pdf"></i> PDF
                    (Visible)</button>
                <button class="btn btn-danger btn-sm" id="pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-success btn-sm" id="excel"><i class="fas fa-file-excel"></i> Excel</button>
            </div>
        </div>

        <table id="notUpdateCasesTable" class=" table-responsiv table table-bordered table-hover text-white"
            style="width: 100%">
            <thead>
                <tr>
                    <th>File No</th>
                    <th>Case Type</th>
                    <th>Case No</th>
                    <th>Court</th>
                    <th>Police Station</th>
                    <th>1<sup>st</sup> Party</th>
                    <th>2<sup>nd</sup> Party</th>
                    <th>Law & Section</th>
                    <th>Previous Date</th>
                    <th>Next Date</th>
                    <th>Fixed For</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
              // Get current date in 'YYYY-MM-DD' format
$currentdate = date('d-m-Y');

$sql = "
    SELECT c.id,
           c.fileNo,
           c.caseNo, 
           ct.name, 
           co.court_name, 
           ps.name, 
           c.date,
            c.fixedFor,
             c.firstParty,
              c.secondParty, 
           c.mobileNo,
            c.appointedBy,
             c.lawSection, 
           c.comments,
            c.status,
             c.hearing_date,
              c.last_updated
    FROM cases c 
    LEFT JOIN case_types ct ON c.case_types = ct.id
    LEFT JOIN courts co ON c.court = co.id
    LEFT JOIN police_stations ps ON c.police_stations = ps.id
    WHERE c.status = 'Running';
";


                $result = $conn->query($sql);
                $rowCount = $result->num_rows;

                if ($rowCount > 0) {
                    while ($row = $result->fetch_assoc()) {


                         // Convert date format to d-m-Y
                    $previous_date = date("d-m-Y", strtotime($row['date']));
                    $hearing_date = date("d-m-Y", strtotime($row['hearing_date']));
                        echo "<tr>
                        <td>{$row['fileNo']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['caseNo']}</td>
                        <td>{$row['court_name']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['firstParty']}</td>
                        <td>{$row['secondParty']}</td>
                        <td>{$row['lawSection']}</td>
                         <td>{$previous_date}</td>
                        <td>{$hearing_date}</td>
                        <td>{$row['fixedFor']}</td>
                        <td><span class='badge badge-success'>{$row['status']}</span></td>
                        <td>
                            <div class='dropdown'>
                                <button class='btn btn-custom btn-sm dropdown-toggle' type='button' data-toggle='dropdown'>
                                    <i class='fas fa-cogs'></i> Actions
                                </button>
                                <div class='dropdown-menu action-menu'>
                                    <a href='./Case/add_date.php?id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-calendar-plus'></i> Add Next Date</a>
                                    <a href='./Case/edit_case.php?id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-edit'></i> Edit Case</a>
                                    <a href='./Case/add_party.php?case_id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-user-plus'></i> Add Party Details</a>
                                    <a href='./Case/add_details.php?case_id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-file-alt'></i> Add More Details</a>
                                    <a href='./Case/Add_file.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-file-pdf'></i>Upload File</a>
                                    <a href='./Case/add_payment.php?case_id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-dollar-sign'></i> Add Payment</a>
                                    <a href='./Case/show_details.php?id={$row['id']}' class='dropdown-item'><i
                                            class='fas fa-eye'></i> Show/Edit Details</a>
                                    <a href='./Case/delete.php?id={$row['id']}' class='dropdown-item text-danger'><i
                                            class='fas fa-trash'></i> Delete Case</a>
                                </div>
                            </div>
                        </td>
                    </tr>";
                    
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No cases found that haven't been updated</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#notUpdateCasesTable').DataTable({
            lengthMenu: [10, 25, 50, 100],
            paging: true,
            searching: true
        });

        // Disable export buttons if no data
        if (<?php echo $rowCount; ?> === 0) {
            $("#pdf, #pdf-visible, #excel").prop("disabled", true);
        }
    });

    document.getElementById("pdf-visible").addEventListener("click", function() {
        downloadPDF("visible");
    });

    document.getElementById("pdf").addEventListener("click", function() {
        downloadPDF("full");
    });

    document.getElementById("excel").addEventListener("click", function() {
        downloadExcel();
    });

    function downloadPDF(type) {
        let table = document.getElementById("notUpdateCasesTable");
        let opt = {
            margin: 10,
            filename: "Case_Management.pdf",
            image: {
                type: "jpeg",
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: "mm",
                format: "a4",
                orientation: "landscape"
            },
        };

        html2pdf().set(opt).from(table).save();
    }

    function downloadExcel() {
        let table = document.getElementById("notUpdateCasesTable");
        let wb = XLSX.utils.table_to_book(table, {
            sheet: "Sheet1"
        });
        XLSX.writeFile(wb, "Case_Management.xlsx");
    }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/js/misc.js"></script>
</body>

</html>