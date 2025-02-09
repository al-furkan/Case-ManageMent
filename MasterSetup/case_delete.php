<?php

include './../db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Delete all cases related to this court (use carefully)
    $deleteCasesQuery = "DELETE FROM cases WHERE case_types = ?";
    $stmtCases = $conn->prepare($deleteCasesQuery);
    $stmtCases->bind_param("i", $id);
    $stmtCases->execute();
    $stmtCases->close();

    // Now delete the court
    $deleteCourtQuery = "DELETE FROM case_types WHERE id = ?";
    $stmtCourt = $conn->prepare($deleteCourtQuery);
    $stmtCourt->bind_param("i", $id);

    if ($stmtCourt->execute()) {
        echo "<script>alert('Court deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmtCourt->error . "');</script>";
    }

    echo "<script>window.open('../index.php?casetype=1', '_self');</script>";
    $stmtCourt->close();
} else {
    echo "<script>alert('Invalid ID!');</script>";
    echo "<script>window.open('../index.php?casetype=1', '_self');</script>";
}
?>