<?php
require 'vendor/autoload.php'; // Load PHPExcel via Composer
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "case-management";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$sheet->setCellValue('A1', 'File No')
      ->setCellValue('B1', 'Case Type')
      ->setCellValue('C1', 'Case No')
      ->setCellValue('D1', 'Court')
      ->setCellValue('E1', 'Police Station')
      ->setCellValue('F1', 'Law & Section')
      ->setCellValue('G1', 'Previous Date')
      ->setCellValue('H1', 'Next Date')
      ->setCellValue('I1', 'Fixed For')
      ->setCellValue('J1', 'Status');

// Fetch case records
$sql = "SELECT `fileNo`, `case_types`, `caseNo`, `court`, `police_stations`, `lawSection`, `date`, `fixedFor`, `status` FROM `cases`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rowIndex = 2; // Start inserting data from row 2
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $row['fileNo'])
              ->setCellValue('B' . $rowIndex, $row['case_types'])
              ->setCellValue('C' . $rowIndex, $row['caseNo'])
              ->setCellValue('D' . $rowIndex, $row['court'])
              ->setCellValue('E' . $rowIndex, $row['police_stations'])
              ->setCellValue('F' . $rowIndex, $row['lawSection'])
              ->setCellValue('G' . $rowIndex, $row['date'])
              ->setCellValue('H' . $rowIndex, $row['date'])
              ->setCellValue('I' . $rowIndex, $row['fixedFor'])
              ->setCellValue('J' . $rowIndex, $row['status']);
        $rowIndex++;
    }
}

// Set the header to download the file
$filename = "cases_export.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>