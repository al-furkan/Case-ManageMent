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

// Get DataTable parameters
$limit = isset($_POST['length']) ? (int)$_POST['length'] : 10; // Number of records per page
$offset = isset($_POST['start']) ? (int)$_POST['start'] : 0; // Offset for pagination
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : ''; // Search input

// Base SQL Query
$sql = "SELECT * FROM cases";
$sql_count = "SELECT COUNT(*) AS total FROM cases"; // Get total records count

// Apply Search Filter
if (!empty($search)) {
    $sql .= " WHERE 
        fileNo LIKE '%$search%' OR 
        case_types LIKE '%$search%' OR 
        caseNo LIKE '%$search%' OR 
        court LIKE '%$search%' OR 
        police_stations LIKE '%$search%' OR 
        lawSection LIKE '%$search%' OR 
        fixedFor LIKE '%$search%' OR 
        status LIKE '%$search%'";

    $sql_count .= " WHERE 
        fileNo LIKE '%$search%' OR 
        case_types LIKE '%$search%' OR 
        caseNo LIKE '%$search%' OR 
        court LIKE '%$search%' OR 
        police_stations LIKE '%$search%' OR 
        lawSection LIKE '%$search%' OR 
        fixedFor LIKE '%$search%' OR 
        status LIKE '%$search%'";
}

// Get total filtered records count
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_filtered = $row_count['total'];

// Apply Pagination
$sql .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'fileNo' => $row['fileNo'],
        'case_types' => $row['case_types'],
        'caseNo' => $row['caseNo'],
        'court' => $row['court'],
        'police_stations' => $row['police_stations'],
        'lawSection' => $row['lawSection'],
        'previous_date' => $row['date'], // Update column name if different
        'next_date' => $row['date'], // Update column name if different
        'fixedFor' => $row['fixedFor'],
        'status' => "<span class='badge badge-success'>{$row['status']}</span>",
        'action' => "
            <div class='dropdown'>
                <button class='btn btn-custom btn-sm dropdown-toggle' type='button' data-toggle='dropdown'>
                    <i class='fas fa-cogs'></i> Actions
                </button>
                <div class='dropdown-menu action-menu'>
                    <a href='./Case/add_date.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-calendar-plus'></i> Add Next Date</a>
                    <a href='./Case/edit_case.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-edit'></i> Edit Case</a>
                    <a href='./Case/add_party.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-user-plus'></i> Add Party Details</a>
                    <a href='./Case/add_details.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-file-alt'></i> Add More Details</a>
                    <a href='./Case/add_payment.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-dollar-sign'></i> Add Payment</a>
                    <a href='./Case/show_details.php?id={$row['id']}' class='dropdown-item'><i class='fas fa-eye'></i> Show/Edit Details</a>
                    <a href='./Case/delete.php?id={$row['id']}' class='dropdown-item text-danger'><i class='fas fa-trash'></i> Delete Case</a>
                </div>
            </div>"
    ];
}

// Return JSON response
$response = [
    'draw' => isset($_POST['draw']) ? (int)$_POST['draw'] : 1,
    'recordsTotal' => $row_count['total'], // Total records before filtering
    'recordsFiltered' => $total_filtered, // Total records after filtering
    'data' => $data
];

echo json_encode($response);

// Close the connection
$conn->close();
?>