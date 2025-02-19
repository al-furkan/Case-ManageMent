<?php
require_once('tcpdf/tcpdf.php');
$servername = "localhost";
$username = "root";
$password = "";
$database = "case-management";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Case Management System');
$pdf->SetTitle('Cases Report');
$pdf->SetHeaderData('', 0, 'Case Management Report', "Generated on: " . date('Y-m-d H:i:s'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Fetch case data
$sql = "SELECT fileNo, case_types, caseNo, court, police_stations, lawSection, date, fixedFor, status FROM cases";
$result = $conn->query($sql);

// Generate table HTML
$html = '<h2 style="text-align:center;">Case Management Report</h2>';
$html .= '<table border="1" cellpadding="5">
            <tr style="background-color:#f2f2f2;">
                <th><b>File No</b></th>
                <th><b>Case Type</b></th>
                <th><b>Case No</b></th>
                <th><b>Court</b></th>
                <th><b>Police Station</b></th>
                <th><b>Law & Section</b></th>
                <th><b>Date</b></th>
                <th><b>Fixed For</b></th>
                <th><b>Status</b></th>
            </tr>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . $row["fileNo"] . '</td>
                    <td>' . $row["case_types"] . '</td>
                    <td>' . $row["caseNo"] . '</td>
                    <td>' . $row["court"] . '</td>
                    <td>' . $row["police_stations"] . '</td>
                    <td>' . $row["lawSection"] . '</td>
                    <td>' . $row["date"] . '</td>
                    <td>' . $row["fixedFor"] . '</td>
                    <td>' . $row["status"] . '</td>
                  </tr>';
    }
} else {
    $html .= '<tr><td colspan="9" style="text-align:center;">No cases found</td></tr>';
}

$html .= '</table>';

// Output HTML to PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('case_report.pdf', 'D'); // Forces download

$conn->close();
?>