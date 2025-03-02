<?php
require_once __DIR__ . '/vendor/autoload.php'; // Include mPDF library

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'case-management';

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get case ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid case ID.");
}

$case_id = intval($_GET['id']); // Sanitize input

// Fetch case details
$sql = "SELECT * FROM cases WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$case_result = $stmt->get_result();

if ($case_result->num_rows == 0) {
    die("Case not found.");
}
$case = $case_result->fetch_assoc();

// Fetch party details
$sql_party = "SELECT * FROM party_details WHERE case_id = ?";
$stmt = $conn->prepare($sql_party);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$party_result = $stmt->get_result();

// Fetch additional case details
$sql_case_details = "SELECT * FROM case_details WHERE case_id = ?";
$stmt = $conn->prepare($sql_case_details);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$case_details_result = $stmt->get_result();

// Fetch payment details
$sql_payment = "SELECT * FROM payments WHERE case_id = ?";
$stmt = $conn->prepare($sql_payment);
$stmt->bind_param("i", $case_id);
$stmt->execute();
$payment_result = $stmt->get_result();

// Start generating PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Case Details PDF");

// PDF Content
$html = "
<h2>Case Details</h2>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<tr><th>File No:</th><td>{$case['fileNo']}</td></tr>
<tr><th>Case No:</th><td>{$case['caseNo']}</td></tr>
<tr><th>Type:</th><td>{$case['case_types']}</td></tr>
<tr><th>Court:</th><td>{$case['court']}</td></tr>
<tr><th>Police Station:</th><td>{$case['police_stations']}</td></tr>
<tr><th>Law & Section:</th><td>{$case['lawSection']}</td></tr>
<tr><th>Fixed For:</th><td>{$case['fixedFor']}</td></tr>
<tr><th>Status:</th><td>{$case['status']}</td></tr>
<tr><th>Hearing Date:</th><td>{$case['hearing_date']}</td></tr>
</table>";

$html .= "<h2>Party Details</h2>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<tr><th>Name</th><th>Email</th><th>Mobile</th><th>Type</th><th>Address</th></tr>";

while ($party = $party_result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$party['name']}</td>
        <td>{$party['email']}</td>
        <td>{$party['mobile_no']}</td>
        <td>{$party['party_type']}</td>
        <td>{$party['address']}</td>
    </tr>";
}
$html .= "</table>";

$html .= "<h2>Additional Case Details</h2>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<tr><th>Description</th><th>Case Laws</th><th>Total Fees</th></tr>";

while ($case_detail = $case_details_result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$case_detail['description']}</td>
        <td>{$case_detail['case_laws']}</td>
        <td>{$case_detail['total_fees']}</td>
    </tr>";
}
$html .= "</table>";

$html .= "<h2>Payment Details</h2>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<tr><th>Payment ID</th><th>Name</th><th>Type</th><th>Amount</th><th>Date</th></tr>";

while ($payment = $payment_result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$payment['payment_id']}</td>
        <td>{$payment['name']}</td>
        <td>{$payment['payment_type']}</td>
        <td>{$payment['amount']}</td>
        <td>{$payment['payment_date']}</td>
    </tr>";
}
$html .= "</table>";

// Convert to PDF
$mpdf->WriteHTML($html);
$mpdf->Output("case_details_{$case_id}.pdf", "D");
?>