<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION["id"];
$jpid = isset($_GET['jpid']) ? $_GET['jpid'] : '';

include '../api/db_connection.php'; // Include your database connection


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle text input fields
    $username = $_POST["username"];
    $phoneNumber = $_POST["phonenumber"];
    $icNumber = $_POST["icnumber"];
    $emailAddress = $_POST["email"];
    $fullName = $_POST["fullname"];

    // Update the user profile information
    $upd = "UPDATE user 
            SET phoneNumber = '$phoneNumber', icNumber = '$icNumber', emailAddress = '$emailAddress', fullName = '$fullName'
            WHERE userName = '$username';";
    $result = mysqli_query($conn, $upd);

    if ($result) {
        // Handle file upload for the image
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = file_get_contents($_FILES["image"]["tmp_name"]);
            $updateImageQuery = "UPDATE user SET image = ? WHERE userName = ?";
            
            $stmt = $conn->prepare($updateImageQuery);
            $stmt->bind_param("bi", $image, $username);
            $stmt->send_long_data(0, $image);
            $stmt->execute();
        }

        header("Location: ../customer-update.php?jpid=$jpid");
        exit;
    } else {
        echo "Error updating user profile: " . $conn->error;
    }

}

$conn->close();
?>
