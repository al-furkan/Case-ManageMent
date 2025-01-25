<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Case Management</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"
  />
  <style>
    body {
      background-color: #f8f9fa;
    }
    table th, table td {
      text-align: center;
      vertical-align: middle;
    }
    .dropdown-menu .dropdown-item:hover {
      background-color: #f8f9fa;
    }
    .action-menu {
      min-width: 160px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>All Cases</h1>
      <div>
        <button class="btn btn-info btn-sm" id="pdf-visible">PDF as Visible</button>
        <button class="btn btn-success btn-sm" id="pdf">PDF</button>
        <button class="btn btn-primary btn-sm" id="excel">Excel</button>
      </div>
    </div>

    <table
      id="casesTable"
      class="table table-bordered table-hover"
      style="width: 100%"
    >
      <thead class="thead-light">
        <tr>
          <th>File No</th>
          <th>Case Type</th>
          <th>Case No</th>
          <th>Court</th>
          <th>Police Station</th>
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

$sql = "SELECT `id`, `fileNo`, `caseNo`, `caseType`, `court`, `policeStation`, 
`date`, `fixedFor`, `firstParty`, `secondParty`, `mobileNo`, `appointedBy`, 
`lawSection`, `comments`, `status` FROM `cases`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['fileNo']}</td>
            <td>{$row['caseType']}</td>
            <td>{$row['caseNo']}</td>
            <td>{$row['court']}</td>
            <td>{$row['policeStation']}</td>
            <td>{$row['lawSection']}</td>
            <td>{$row['date']}</td> <!-- Updated Previous Date -->
            <td>{$row['date']}</td> <!-- Updated Next Date -->
            <td>{$row['fixedFor']}</td>
            <td><span class='badge badge-success'>{$row['status']}</span></td>
            <td>
                <div class='dropdown'>
                    <button class='btn btn-primary btn-sm dropdown-toggle' type='button' data-toggle='dropdown'>
                        Actions
                    </button>
                    <div class='dropdown-menu action-menu'>
                        <a href='add_date.php?id={$row['id']}' class='dropdown-item'>Add Next Date</a>
                        <a href='edit_case.php?id={$row['id']}' class='dropdown-item'>Edit Case</a>
                        <a href='add_party.php?id={$row['id']}' class='dropdown-item'>Add Party Details</a>
                        <a href='add_details.php?id={$row['id']}' class='dropdown-item'>Add More Details</a>
                        <a href='add_payment.php?id={$row['id']}' class='dropdown-item'>Add Payment</a>
                        <a href='show_details.php?id={$row['id']}' class='dropdown-item'>Show/Edit Details</a>
                        <a href='delete_case.php?id={$row['id']}' class='dropdown-item text-danger'>Delete Case</a>
                    </div>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='15'>No cases found</td></tr>";
}
?>

      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script
    src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"
  ></script>
  <script
    src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"
  ></script>
  <script>
    $(document).ready(function () {
      $('#casesTable').DataTable({
        lengthMenu: [10, 25, 50, 100],
      });

      // Placeholder for PDF/Excel downloads
      $('#pdf').click(function () {
        alert('Generate full PDF report!');
      });

      $('#pdf-visible').click(function () {
        alert('Generate visible PDF report!');
      });

      $('#excel').click(function () {
        alert('Generate Excel report!');
      });
    });
  </script>
</body>
</html>
