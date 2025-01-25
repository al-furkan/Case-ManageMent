<?php
include './../db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM notes WHERE id = $id";
  $result = $conn->query($sql);
  $note = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id'];
  $note_date = $_POST['note_date'];
  $note_content = $_POST['note_content'];

  $sql = "UPDATE notes SET note_date='$note_date', note_content='$note_content' WHERE id=$id";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Notes Update successfully!');</script>";
    echo "<script>window.open('../index.php?dailynotes=1', '_self');</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Note</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .form-container {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 5px;
    }

    .btn-primary {
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <h1 class="form-title">Edit Note</h1>
      <form action="edit.php" method="post" id="editForm">
        <!-- Hidden Input for Note ID -->
        <input type="hidden" name="id" value="<?= $note['id'] ?>" />

        <!-- Date Input -->
        <div class="mb-3">
          <label for="noteDate" class="form-label">Note Date</label>
          <input type="date" class="form-control" id="noteDate" name="note_date" value="<?= $note['note_date'] ?>" required />
        </div>

        <!-- Textarea for Note Content -->
        <div class="mb-3">
          <label for="noteContent" class="form-label">Note Content</label>
          <textarea class="form-control" id="noteContent" name="note_content" rows="4" required><?= $note['note_content'] ?></textarea>
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Update Note</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for Form Validation -->
  <script>
    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function (event) {
      const noteContent = document.getElementById('noteContent').value.trim();
      if (!noteContent) {
        event.preventDefault();
        alert('Note content cannot be empty.');
      }
    });
  </script>
</body>

</html>
