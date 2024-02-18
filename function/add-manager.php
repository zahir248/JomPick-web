<?php
session_start();

include '../api/db_connection.php'; // Include your database connection

try {
    // Check if the user is logged in, redirect to the login page if not
    if (!isset($_SESSION["id"])) {
        header("Location: ../index.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userName = $_POST["username"];
        $password = $_POST["password"];
        $phoneNumber = $_POST["phonenumber"];
        $icNumber = $_POST["icnumber"];
        $emailAddress = $_POST["email"];
        $fullName = $_POST["fullname"];
        $user_id = $_SESSION["id"];
        $locations = $_POST["locationabc"];

        // Check if any of the fields is empty
        if (empty($userName) || empty($password) || empty($phoneNumber) || empty($icNumber) || empty($emailAddress) || empty($fullName)) {
            header("Location: ../manager-register.php?error=Input Can't Be Empty");
            exit();
        }

        // Validate password
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z\d]).{6,}$/', $password)) {
            header("Location: ../manager-register.php?error=Invalid Password");
            exit();
        }

        // Validate phone number (adjust the regex as needed)
        if (!preg_match("/^[0-9]{10,11}$/", $phoneNumber)) {
            header("Location: ../manager-register.php?error=Invalid Phone Number");
            exit();
        }

        // Validate IC number (adjust the regex as needed)
        if (!preg_match("/^[0-9]{12}$/", $icNumber)) {
            header("Location: ../manager-register.php?error=Invalid IC Number");
            exit();
        }

        // Validate email address
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../manager-register.php?error=Invalid Email");
            exit();
        }

        $role_id = 2; // Set role_id to 2 Manager
        // Validate image file
        // Check if the file is uploaded
        if (empty($_FILES["image"]["tmp_name"]) || !file_exists($_FILES["image"]["tmp_name"])) {
            header("Location: ../manager-register.php?error=Image File is Required");
            exit;
        }

        // Check file type
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $uploadedFileType = $_FILES["image"]["type"];

        if (!in_array($uploadedFileType, $allowedFileTypes)) {
            header("Location: ../manager-register.php?error=Invalid File Type. Only JPEG, PNG, and JPG are allowed.");
            exit;
        }

        // Check file size (max 5 MB)
        $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
        if ($_FILES["image"]["size"] > $maxFileSize) {
            header("Location: ../manager-register.php?error=File size exceeds the maximum limit of 5 MB.");
            exit;
        }

        $image = file_get_contents($_FILES["image"]["tmp_name"]);
        $availability_id = 1;

        // Check if the username already exists
        $checkUsernameQuery = "SELECT user_id FROM user WHERE userName = ?";
        $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
        $checkUsernameStmt->bind_param("s", $userName);
        $checkUsernameStmt->execute();
        $checkUsernameResult = $checkUsernameStmt->get_result();

        if ($checkUsernameResult->num_rows > 0) {
            // Username already exists, handle the error
            header("Location: ../manager-register.php?error=Username already exists");
            exit();
        }

        // Fetch the current maximum value from the 'user' table using prepared statements
        $query = "SELECT MAX(CAST(SUBSTRING(JomPick_ID, 3) AS UNSIGNED)) AS max_id FROM user WHERE role_id = 2;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $maxId = $row['max_id'];
            // Increment the maximum value
            $nextId = $maxId + 1;

            // Format the next ID with leading zeros
            $jp_id = 'EM' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        } else {
            // Default value if there are no existing records
            $jp_id = 'EM001';
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements for the insertion query
        $insertQuery = "INSERT INTO user (userName, password, phonenumber, icNumber, emailAddress, fullName, role_id, jp_location_id, manager_id, availability_id, image, JomPick_ID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt) {
            $stmt->bind_param("ssssssiiiiss", $userName, $hashPassword, $phoneNumber, $icNumber, $emailAddress, $fullName, $role_id, $locations, $user_id, $availability_id, $image, $jp_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Close the statement
                $stmt->close();

                // Introduce a delay of 2 seconds (adjust as needed)
                sleep(2);

                header("Location: ../manager-register.php?success=New Manager Added Successfully");
                exit();
            } else {
                // Close the statement
                $stmt->close();
                header("Location: ../manager-register.php?error=Error Adding Manager");
                exit();
            }
        } else {
            // Close the statement
            $stmt->close();
            header("Location: ../staff-register.php?error=Error Preparing Statement");
            exit();
        }
    }
} catch (Exception $e) {
    header("Location: ../manager-register.php?error=" . urlencode($e->getMessage()));
    exit();
} finally {
    // Close the database connection
    $conn->close();
}
?>