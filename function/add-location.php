<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

include '../api/db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validate location
    
    $address = trim($_POST["regAddress"]);
    $location = trim($_POST["reglocation"]);
    if (empty($location)) {
        header("Location: ../location-register.php?error=Location Name is Required&reglocation=$location&regAddress=$address");
        exit;
    }

    if (empty($address)) {
        header("Location: ../location-register.php?error=Location Address is Required&reglocation=$location&regAddress=$address");
        exit;
    }


    // Validate image file
    // Check if the file is uploaded
    if (empty($_FILES["image"]["tmp_name"]) || !file_exists($_FILES["image"]["tmp_name"])) {
        header("Location: ../location-register.php?error=Image File is Required&reglocation=$location&regAddress=$address");
        exit;
    }

    // Check file type
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $uploadedFileType = $_FILES["image"]["type"];

    if (!in_array($uploadedFileType, $allowedFileTypes)) {
        header("Location: ../location-register.php?error=Invalid File Type. Only JPEG, PNG, and JPG are allowed.&reglocation=$location");
        exit;
    }

    // Check file size (max 5 MB)
    $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
    if ($_FILES["image"]["size"] > $maxFileSize) {
        header("Location: ../location-register.php?error=File size exceeds the maximum limit of 5 MB.&reglocation=$location");
        exit;
    }

    $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
    $availabilityId = 1; // Assuming availability_id is an integer

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO pickup_location (name, address, image, availability_id) VALUES (?, ?, ?,?)");

    if ($stmt) {
        $stmt->bind_param("sssi", $location, $address, $imageData, $availabilityId);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../location-register.php?success=Location Added Successfully");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>