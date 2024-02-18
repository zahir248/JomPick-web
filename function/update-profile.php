<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION["id"];

include '../api/db_connection.php'; // Include your database connection


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle text input fields
    $username = $_POST["username"];
    $phoneNumber = (string)$_POST["phonenumber"]; // Explicitly cast to string
    $icNumber = $_POST["icnumber"];
    $emailAddress = $_POST["email"];
    $fullName = $_POST["fullname"];

    if (empty($username) || empty($phoneNumber) || empty($icNumber) || empty($emailAddress) || empty($fullName)) {
        header("Location: ../user-profile.php?error=All Fields are Required.");
        exit;
    }

    // Validate email format
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../user-profile.php?error=Invalid Email.");
        exit;
    }

    // Update the user profile information
    $upd = "UPDATE user 
            SET phoneNumber = '$phoneNumber', icNumber = '$icNumber', emailAddress = '$emailAddress', fullName = '$fullName'
            WHERE user_id = '$user_id';";
    $result = mysqli_query($conn, $upd);

    if ($result) {
        // Handle file upload for the image
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = file_get_contents($_FILES["image"]["tmp_name"]);
            $updateImageQuery = "UPDATE user SET image = ? WHERE user_id = ?";
            
            $stmt = $conn->prepare($updateImageQuery);
            $stmt->bind_param("bi", $image, $user_id);
            $stmt->send_long_data(0, $image);
            $stmt->execute();
        }

        header("Location: ../user-profile.php?success=Profile Updated Successfully.");
        exit;
    } else {
        echo "Error updating user profile: " . $conn->error;
    }

}

$conn->close();
?>
