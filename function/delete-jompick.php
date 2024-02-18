<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$resitid = isset($_GET['resitid']) ? $_GET['resitid'] : '';
$c_id = isset($_GET['c_id']) ? $_GET['c_id'] : '';
$user_id = $_SESSION["id"];

include '../api/db_connection.php'; // Include your database connection

$dlt = "UPDATE confirmation SET confirmationStatus_id  = 4 WHERE confirmation_id = '$c_id';";
$aa  = mysqli_query($conn, $dlt);

if ($aa === TRUE) {
    header("Location: ../jompick-list.php");
    exit;
} else {
    echo "Error updating confirmation status: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

