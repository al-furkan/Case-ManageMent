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
    <title>Decided Cases</title>
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
        text-align: center;
        vertical-align: middle;
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

    .badge-info {
        background-color: #17a2b8;
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
            <h1><i class="fas fa-gavel"></i> Decided Cases</h1>
            <div>
                <button class="btn btn-dark btn-sm" id="pdf-visible"><i class="fas fa-file-pdf"></i> PDF
                    (Visible)</button>
                <button class="btn btn-danger btn-sm" id="pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-success btn-sm" id="excel"><i class="fas fa-file-excel"></i> Excel</button>
            </div>
        </div>

        <table id="decidedCasesTable" class="table table-bordered table-hover text-white" style="width: 100%">
            <thead>
                <tr>
                    <th>File No</th>
                    <th>Case Type</th>
                    <th>Case No</th>
                    <th>Court</th>
                    <th>Police Station</th>
                    <th>Law & Section</th>
                    <th>Hearing Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL query to fetch decided cases (status = 'Decided')
                $sql = "SELECT * FROM cases WHERE status = 'Decided'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['fileNo']}</td>
                            <td>{$row['case_types']}</td>
                            <td>{$row['caseNo']}</td>
                            <td>{$row['court']}</td>
                            <td>{$row['police_stations']}</td>
                            <td>{$row['lawSection']}</td>
                            <td>{$row['hearing_date']}</td>
                            <td><span class='badge badge-info'>{$row['status']}</span></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No decided cases found</td></tr>";
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
        $('#runningCasesTable').DataTable({
            lengthMenu: [10, 25, 50, 100],
            paging: true,
            searching: true
        });
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
        let table = document.querySelector("table"); // Adjust selector as needed
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

        html2pdf()
            .set(opt)
            .from(table)
            .save();
    }

    function downloadExcel() {
        let table = document.querySelector("table"); // Adjust selector as needed
        let wb = XLSX.utils.table_to_book(table, {
            sheet: "Sheet1"
        });
        XLSX.writeFile(wb, "Case_Management.xlsx");
    }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
</body>

</html>