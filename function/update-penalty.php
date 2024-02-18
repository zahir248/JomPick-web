<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$statuspayment = isset($_GET['statuspayment']) ? $_GET['statuspayment'] : '';
$p_id = isset($_GET['p_id']) ? $_GET['p_id'] : '';
$user_id = $_SESSION["id"];

include '../api/db_connection.php'; // Include your database connection

if ($statuspayment == 4) {
    $dlt = "UPDATE payment SET paymentStatus_id = 3 WHERE payment_id = '$p_id';"; 
}

if ($statuspayment == 3) {
    $dlt = "UPDATE payment SET paymentStatus_id = 4 WHERE payment_id = '$p_id';"; 
}

if ($statuspayment == 1) {
    header("Location: ../jompick-penalty-list.php");
    exit();
}

if ($statuspayment == 2) {
    header("Location: ../jompick-penalty-list.php");
    exit();
}


$aa  = mysqli_query($conn, $dlt);

if ($conn->query($dlt) === TRUE) {
    header("Location: ../jompick-penalty-list.php");
    exit;
} else {
    echo "Error adding user: " . $conn->error;
}

$conn->close();
?>