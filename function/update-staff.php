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

    // Check if any of the text input fields is empty
    if (empty($username) || empty($phoneNumber) || empty($icNumber) || empty($emailAddress) || empty($fullName)) {
        header("Location: ../staff-update.php?jpid=$jpid&error=Input Can't Be Empty");
        exit();
    }

    // Validate phone number
    if (!preg_match("/^[0-9]{10,11}$/", $phoneNumber)) {
        header("Location: ../staff-update.php?jpid=$jpid&error=Invalid Phone Number");
        exit();
    }

    // Validate email address
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../staff-update.php?jpid=$jpid&error=Invalid Email Address");
        exit();
    }

    // Validate IC number (Assuming a simple format)
    if (!preg_match("/^[0-9]{12}$/", $icNumber)) {
        header("Location: ../staff-update.php?jpid=$jpid&error=Invalid IC Number");
        exit();
    }

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

        header("Location: ../staff-update.php?jpid=$jpid&success=Staff Details Updated Successful");
        exit;
    } else {
        echo "Error updating user profile: " . $conn->error;
    }

}

$conn->close();
?>
