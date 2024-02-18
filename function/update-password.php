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
    // Process the form
    $oldpassword = $_POST["oldpassword"];
    $newpassword = $_POST["newpassword"];
    

    // Check if the old password matches the current password in the database
    $checkPasswordQuery = "SELECT password FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($checkPasswordQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($currentPassword);
    $stmt->fetch();
    $stmt->close();

    if ($oldpassword === $currentPassword) {
        // Old password matches, proceed to update the password
        $updatePasswordQuery = "UPDATE user SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($updatePasswordQuery);
        $stmt->bind_param("si", $newpassword, $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: ../user-profile.php?success=Password changed successfully");
        exit;
    } else {
        // Old password does not match
        header("Location: ../user-profile.php?error=Old Password Mismatch");
        exit;
    }
}

$conn->close();
?>