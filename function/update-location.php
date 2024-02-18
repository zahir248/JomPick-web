<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

include '../api/db_connection.php'; // Include your database connection

// Variables for location and image
$location_id = isset($_GET['location']) ? $_GET['location'] : '';
$user_id = $_SESSION["id"];
$location = $_POST['locations'];


if (!empty($location)) {

    $address = trim($_POST["regAddress"]);

    if (empty($address)) {
        header("Location: ../location-update.php?error=Location Address is Required&location=$location_id");
        exit;
    }

    // Check if the image is uploaded
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Get image data
        $image = file_get_contents($_FILES["image"]["tmp_name"]);

        // Validate image file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($_FILES["image"]["type"], $allowedTypes) && $_FILES["image"]["size"] <= $maxSize) {

            // Update the database with the new image data
            if (!empty($location_id) && is_numeric($location_id)) {

                // Use prepared statement to prevent SQL injection
                $update_image_query = "UPDATE pickup_location SET name=? , address = ?, image = ? WHERE pickupLocation_id = ?";
                $stmt = $conn->prepare($update_image_query);
                $stmt->bind_param("sssi", $location, $address, $image, $location_id);

                if ($stmt->execute()) {
                    header("Location: ../location-update.php?success=Location Information Successfully Updated.&locatios=$location_id");
                    exit;
                } else {
                    header("Location: ../location-update.php?error=Error updating address: $stmt->error&location=$location_id");
                }

                $stmt->close();
            } else {
                header("Location: ../location-update.php?error=Invalid or missing location_id parameter.&location=$location_id");
                exit;
            }
        } else {
            header("Location: ../location-update.php?error=Please upload only JPEG, PNG, or JPG files with a size of less than 5MB.&locations=$location_id");
            exit;
        }
    } else {
        // Image is empty, update only the address
        $update_address_query = "UPDATE pickup_location SET name=? , address = ? WHERE pickupLocation_id = ?";
        $stmt = $conn->prepare($update_address_query);
        $stmt->bind_param("ssi", $location, $address, $location_id);

        if ($stmt->execute()) {
            header("Location: ../location-update.php?success=Location information successfully updated.&location=$location_id");
            exit;
        } else {
            header("Location: ../location-update.php?error=Error updating address: $stmt->error&location=$location_id");
        }

        $stmt->close();
    }
} else {
    header("Location: ../location-update.php?error=Location Name cannot be empty.&location=$location_id");
}

$conn->close();

?>

