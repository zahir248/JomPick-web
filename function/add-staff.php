<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$role = $_SESSION["role_id"];

$locationid = $_SESSION["jp_location_id"];

include '../api/db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $phoneNumber = $_POST["phonenumber"];
    $icNumber = $_POST["icnumber"];
    $emailAddress = $_POST["email"];
    $fullName = $_POST["fullname"];
    $user_id = $_SESSION["id"];
    $userpic = $_POST["userpic"];

    // Check if any of the fields is empty
    if (empty($userName) || empty($password) || empty($phoneNumber) || empty($icNumber) || empty($emailAddress) || empty($fullName)) {
        header("Location: ../staff-register.php?error=Input Can't Be Empty");
        exit();
    }

    // Validate password
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z\d]).{6,}$/', $password)) {
        header("Location: ../staff-register.php?error=Invalid Password");
        exit();
    }

    // Validate phone number (adjust the regex as needed)
    if (!preg_match("/^[0-9]{10,11}$/", $phoneNumber)) {
        header("Location: ../staff-register.php?error=Invalid Phone Number");
        exit();
    }

    // Validate IC number (adjust the regex as needed)
    if (!preg_match("/^[0-9]{12}$/", $icNumber)) {
        header("Location: ../staff-register.php?error=Invalid IC Number");
        exit();
    }

    // Validate email address
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../staff-register.php?error=Invalid Email");
        exit();
    }

    $role_id = 3; // Set role_id to 3 Staff
    // Validate image file
    // Check if the file is uploaded
    if (empty($_FILES["image"]["tmp_name"]) || !file_exists($_FILES["image"]["tmp_name"])) {
        header("Location: ../staff-register.php?error=Image File is Required");
        exit;
    }

    // Check file type
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $uploadedFileType = $_FILES["image"]["type"];

    if (!in_array($uploadedFileType, $allowedFileTypes)) {
        header("Location: ../staff-register.php?error=Invalid File Type. Only JPEG, PNG, and JPG are allowed.");
        exit;
    }

    // Check file size (max 5 MB)
    $maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
    if ($_FILES["image"]["size"] > $maxFileSize) {
        header("Location: ../staff-register.php?error=File size exceeds the maximum limit of 5 MB.");
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
        header("Location: ../staff-register.php?error=Username already exists");
        exit();
    }

    if ($role == 1 ){
        $sql9 = "SELECT jp_location_id FROM user WHERE user_id = $userpic; "; 

        $result9 = mysqli_query($conn, $sql9);
        $row9 =mysqli_fetch_array($result9,MYSQLI_ASSOC);
        
        $location = $row9['jp_location_id'];
        
    }

    if ($role == 2 ){
        $userpic = $user_id;
        $location = $locationid;
    }

    // Fetch the current maximum value from the 'item' table
    $query = "SELECT MAX(CAST(SUBSTRING(JomPick_ID, 3) AS UNSIGNED)) AS max_id FROM user WHERE role_id = 3;";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $maxId = $row['max_id'];
        // Increment the maximum value
        $nextId = $maxId + 1;

        // Format the next ID with leading zeros
        $jp_id = 'ES' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    } else {
        // Default value if there are no existing records
        $jp_id = 'ES000000001';
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (userName, password, phonenumber, icNumber, emailAddress, fullName, role_id, jp_location_id, manager_id, availability_id, image,JomPick_ID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssiiiiss", $userName, $hashPassword, $phoneNumber, $icNumber, $emailAddress, $fullName, $role_id, $location, $userpic, $availability_id,$image, $jp_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../staff-register.php?success=New Staff Added Succesful");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    header("Location: ../staff-register.php?error=Error Preparing Statement");
    exit();
}

}

$conn->close();

?>

