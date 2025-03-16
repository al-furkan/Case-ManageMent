<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    body {
        background-color: #1c1c1c;
        color: white;
    }

    .table th,
    .table td {

        font-size: 12px;
    }

    .action-menu {
        min-width: 180px;
    }

    .btn {
        margin-right: 5px;
    }

    .table {
        background-color: #2c2c2c;
        min-height: 300px;
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

    .action-menu {
        background-color: #000;
    }

    .action-menu .dropdown-item {
        color: white;
    }

    .action-menu .dropdown-item:hover {
        background-color: #444;
    }

    .entries-dropdown {
        display: flex;
        align-items: center;
    }

    .entries-dropdown label {
        margin-right: 10px;
        font-size: 16px;
    }

    .dropdown-menu {
        min-width: 60px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .page-link {
        text-decoration: none;
        color: #fff;
        background: black;
        padding: 8px 15px;
        margin: 0 5px;
        border-radius: 5px;
        transition: 0.3s;
        font-size: 14px;
    }

    .page-link:hover {
        background: white;
        color: black;
        border: 1px solid black;
    }

    .page-number {
        font-size: 16px;
        font-weight: bold;
        padding: 8px 12px;
        background: white;
        color: black;
        border: 1px solid black;
        border-radius: 5px;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-balance-scale"></i> Case Management</h1>
            <a href="../index.php" class="btn btn-primary text-decoration-none">Back to Dashboard</a>

            <div>
                <button class="btn btn-dark btn-sm" id="pdf-visible"><i class="fas fa-file-pdf"></i> PDF
                    (Visible)</button>
                <button class="btn btn-danger btn-sm" id="pdf"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-success btn-sm" id="excel"><i class="fas fa-file-excel"></i> Excel</button>
            </div>
        </div>

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Entries Dropdown -->
                <div class="entries-dropdown d-flex align-items-center">
                    <label for="entries" class="mr-2">Show</label>
                    <select id="entries" class="custom-select custom-select-sm form-control form-control-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="ml-2">entries</span>
                </div>

                <!-- Search Box -->
                <div class="input-group w-25">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search cases...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="searchButton"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <table id="casesTable" class="table-responsive table table-bordered table-hover table-striped text-white w-100">
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
                $sql = "SELECT 
                cases.fileNo, 
                case_types.name AS case_types, 
                cases.caseNo, 
                courts.court_name AS court, 
                police_stations.name AS police_stations, 
                cases.firstParty, 
                cases.secondParty, 
                cases.lawSection, 
                cases.date AS previous_date, 
                cases.hearing_date, 
                cases.fixedFor, 
                cases.status, 
                cases.id
            FROM cases
            LEFT JOIN case_types ON cases.case_types = case_types.id
            LEFT JOIN courts ON cases.court = courts.id
            LEFT JOIN police_stations ON cases.police_stations = police_stations.id";
            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Convert date format to d-m-Y
                    $previous_date = date("d-m-Y", strtotime($row['previous_date']));
                    $hearing_date = date("d-m-Y", strtotime($row['hearing_date']));
            
                    echo "<tr>
                        <td>{$row['fileNo']}</td>
                        <td>{$row['case_types']}</td>
                        <td>{$row['caseNo']}</td>
                        <td>{$row['court']}</td>
                        <td>{$row['police_stations']}</td>
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
                                    <a href='./Case/add_date.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-calendar-plus'></i> Add Next Date</a>
                                    <a href='./Case/edit_case.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-edit'></i> Edit Case</a>
                                    <a href='./Case/add_party.php?case_id={$row['id']}' class='dropdown-item'><i class='fas fa-user-plus'></i> Add Party Details</a>
                                    <a href='./Case/add_details.php?case_id={$row['id']}' class='dropdown-item'><i class='fas fa-file-alt'></i> Add More Details</a>
                                    <a href='./Case/Add_file.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-file-pdf'></i> Upload File</a>
                                    <a href='./Case/add_payment.php?case_id={$row['id']}' class='dropdown-item'><i class='fas fa-dollar-sign'></i> Add Payment</a>
                                    <a href='./Case/show_details.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-eye'></i> Show/Edit Details</a>
                                    <a href='./Case/delete.php?id={$row['id']}' class='dropdown-item text-danger'><i class='fas fa-trash'></i> Delete Case</a>
                                </div>
                            </div>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr>
                    <td colspan='15'>No cases found</td>
                </tr>";
            }
            ?>

            </tbody>
        </table>
        <?php
$limit = 10; // Default entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) AS total FROM cases";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $limit);

$sql = "SELECT * FROM cases LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="page-link prev">Previous</a>
            <?php endif; ?>

            <span class="page-number"><?php echo $page; ?></span>

            <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="page-link next">Next</a>
            <?php endif; ?>
        </div>

    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#casesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'fetch_cases.php', // Fetch cases dynamically
                type: 'POST'
            },
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10, // Default 10 entries
            columns: [{
                    data: 'fileNo'
                },
                {
                    data: 'case_types'
                },
                {
                    data: 'caseNo'
                },
                {
                    data: 'court'
                },
                {
                    data: 'police_stations'
                },
                {
                    data: 'lawSection'
                },
                {
                    data: 'previous_date'
                },
                {
                    data: 'next_date'
                },
                {
                    data: 'fixedFor'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                } // Actions column
            ],
            pagingType: "full_numbers", // Ensures Next/Previous work
        });

        // Handle Entries Dropdown Change
        $('#entries').change(function() {
            var limit = $(this).val();
            table.page.len(limit).draw(); // Update table length
        });

        // Search Functionality
        $('#searchButton').click(function() {
            table.search($('#searchBox').val()).draw();
        });

        // Allow "Enter" key for search
        $('#searchBox').on('keypress', function(e) {
            if (e.which === 13) {
                table.search(this.value).draw();
            }
        });

        // Export Buttons
        $('#pdf').click(function() {
            window.location.href = 'export_pdf.php';
        });

        $('#pdf-visible').click(function() {
            alert('Generating visible PDF...');
        });

        $('#excel').click(function() {
            window.location.href = 'export_excel.php';
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
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/js/misc.js"></script>

</body>

</html>