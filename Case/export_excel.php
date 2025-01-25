<?php
require 'vendor/autoload.php'; // For PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
include 'db.php';

$sql = "SELECT `id`, `fileNo`, `caseNo`, `caseType`, `court`, `policeStation`, `date`, 
        `fixedFor`, `firstParty`, `secondParty`, `mobileNo`, `appointedBy`, 
        `lawSection`, `comments`, `status` FROM `cases`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set column headers
    $sheet->setCellValue('A1', 'File No')
          ->setCellValue('B1', 'Case Type')
          ->setCellValue('C1', 'Case No')
          ->setCellValue('D1', 'Court')
          ->setCellValue('E1', 'Police Station')
          ->setCellValue('F1', '1st Party')
          ->setCellValue('G1', '2nd Party')
          ->setCellValue('H1', 'Mobile No')
          ->setCellValue('I1', 'Appointed By')
          ->setCellValue('J1', 'Law Section')
          ->setCellValue('K1', 'Previous Date')
          ->setCellValue('L1', 'Next Date')
          ->setCellValue('M1', 'Fixed For')
          ->setCellValue('N1', 'Status');

    // Populate rows
    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A$rowNumber", $row['fileNo'])
              ->setCellValue("B$rowNumber", $row['caseType'])
              ->setCellValue("C$rowNumber", $row['caseNo'])
              ->setCellValue("D$rowNumber", $row['court'])
              ->setCellValue("E$rowNumber", $row['policeStation'])
              ->setCellValue("F$rowNumber", $row['firstParty'])
              ->setCellValue("G$rowNumber", $row['secondParty'])
              ->setCellValue("H$rowNumber", $row['mobileNo'])
              ->setCellValue("I$rowNumber", $row['appointedBy'])
              ->setCellValue("J$rowNumber", $row['lawSection'])
              ->setCellValue("K$rowNumber", $row['date'])
              ->setCellValue("L$rowNumber", $row['date'])
              ->setCellValue("M$rowNumber", $row['fixedFor'])
              ->setCellValue("N$rowNumber", $row['status']);
        $rowNumber++;
    }

    // Save the Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = "case_data.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save('php://output');
    exit();
} else {
    echo "No data found!";
}
?>
