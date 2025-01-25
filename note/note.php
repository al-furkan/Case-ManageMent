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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $note_date = $_POST['note_date'];
  $note_content = $_POST['note_content'];

  $sql = "INSERT INTO notes (note_date, note_content) VALUES ('$note_date', '$note_content')";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Notes added successfully!');</script>";
    echo "<script>window.open('../index.php?dailynotes=1', '_self');</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
   <!-- #region 
    
   
   -->



   <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notes</title>
  <link
    href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
    table th, table td {
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="text-center mb-4">
      <h1>Notes</h1>
      <p>Write your daily notes</p>
    </div>

    <!-- Form -->
    <form action="./note/note.php" method="post" class="mb-4">
      <div class="form-row align-items-center">
        <div class="col-md-3">
          <input type="date" name="note_date" class="form-control" required />
        </div>
        <div class="col-md-9">
          <textarea
            class="form-control"
            name="note_content"
            rows="2"
            placeholder="Write your note..."
            required
          ></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Create</button>
    </form>

    <!-- Table -->
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>Date</th>
          <th>Task</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM notes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>{$row['note_date']}</td>
              <td>{$row['note_content']}</td>
              <td>
                <a href='./note/edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                <a href='./note/delete.php?id={$row['id']}' class='btn btn-sm btn-danger'>Delete</a>
              </td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='3'>No notes found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
