<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION["id"];

$userrole= $_SESSION["role_id"];

$locationid = $_SESSION["jp_location_id"];

include '../api/db_connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize JomPick ID
    $jpid = isset($_POST["jpid"]) ? filter_var($_POST["jpid"], FILTER_SANITIZE_STRING) : '';
    $jpid = strtolower($jpid);

    // Validate and sanitize Item Name
    $ownername = isset($_POST["ownername"]) ? filter_var($_POST["ownername"], FILTER_SANITIZE_STRING) : '';

    // Validate and sanitize Item Type ID
    $itemType_id = isset($_POST["itemType_id"]) ? filter_var($_POST["itemType_id"], FILTER_VALIDATE_INT) : '';
    
    // Validate and sanitize Tracking Number
    $tracknum = isset($_POST["tracknum"]) ? filter_var($_POST["tracknum"], FILTER_SANITIZE_STRING) : '';

    // Validate and sanitize Location (assuming it's an integer, adjust if necessary)
    $location = isset($_POST["locations"]) ? filter_var($_POST["locations"], FILTER_VALIDATE_INT) : '';

    // Additional validations can be added based on your specific requirements

    // Perform checks on the sanitized values
    if (empty($jpid) || empty($ownername) || $itemType_id === false) {
        // Handle validation errors, for example, redirect to the form page with an error message
        header("Location: ../jompick-register.php?error=Invalid Input Data");
        exit();
    }

    // Validate and handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

        // Check if the file is an image (adjust as needed based on allowed image types)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (!in_array($fileType, $allowedTypes)) {
            // Handle invalid image type
            header("Location: ../jompick-register.php?error=Invalid Image Type");
            exit();
        }

        // Continue with the rest of your code for processing the form data...
    } else {
        // Handle cases where the image file is not uploaded successfully
        header("Location: ../jompick-register.php?error=Error Uploading The Image File");
        exit();
    }

    if($userrole == '1'){
        $location = $_POST["locations"];
    }

    if($userrole == '2' || $userrole == '3'){
        $location = $locationid;
    }

    // header("Location: ../item-register.php?error1=Please Enter $location Tracking Number");
    // exit();

    // Check if the user specified by jpid exists
    $checkUserQuery = "SELECT * FROM user WHERE JomPick_ID = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("s", $jpid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // User doesn't exist, exit the code or redirect to an error page
        header("Location: ../jompick-register.php?errorjpid='User is not found'&jpid=$jpid&ownername=$ownername&itemType_id=$itemType_id&tracknum=$tracknum&location=$location");
        exit();
    }

    // Handle image file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

        // You should validate and sanitize user inputs here

        // Generate the tracking number based on the item type
        if ($itemType_id == 1) {
            // If item type is Document (ID 1), set the tracking number to '-'
            $tracknum = '-';
        } elseif ($itemType_id == 2) {
            if($tracknum == ''){
                header("Location: ../item-register.php?error1=Please Enter Tracking Number");
                exit();
            }
        }

        
        // Fetch the current maximum value from the 'item' table
        $query = "SELECT MAX(CAST(SUBSTRING(jp_item_id, 9) AS UNSIGNED)) AS max_id FROM item";
        $result = $conn->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            $maxId = $row['max_id'];
            // Increment the maximum value
            $nextId = $maxId + 1;

            // Format the next ID with leading zeros
            $jp_item_id = 'JPI' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
        } else {
            // Default value if there are no existing records
            $jp_item_id = 'JPI000000001';
        }

        // Use $jp_item_id in your SQL query or wherever needed

        // Insert the new item into the database using prepared statement
        $stmt = $conn->prepare("INSERT INTO item (jp_item_id, name, image, trackingNumber, itemType_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $jp_item_id, $ownername, $imageData, $tracknum, $itemType_id);

        if ($stmt->execute()) {

            // Fetch the current maximum value from the 'payment' table
            $query = "SELECT MAX(CAST(SUBSTRING(jp_payment_id, 9) AS UNSIGNED)) AS max_id FROM payment";
            $result = $conn->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                $maxId = $row['max_id'];
                // Increment the maximum value
                $nextId = $maxId + 1;

                // Format the next ID with leading zeros
                $jp_payment_id = 'JPP' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
            } else {
                // Default value if there are no existing records
                $jp_payment_id = 'JPP000000001';
            }
            $paymentAmount = 0;
            $sql2 = "INSERT INTO payment (jp_payment_id, paymentAmount, paymentStatus_id) VALUES ('$jp_payment_id', $paymentAmount, 1);";
            $result2 = mysqli_query($conn, $sql2); 

            // Fetch the current maximum value from the 'due_date' table
            $query = "SELECT MAX(CAST(SUBSTRING(jp_dueDate_id, 9) AS UNSIGNED)) AS max_id FROM due_date";
            $result = $conn->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                $maxId = $row['max_id'];
                // Increment the maximum value
                $nextId = $maxId + 1;

                // Format the next ID with leading zeros
                $jp_dueDate_id = 'JPD' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
            } else {
                // Default value if there are no existing records
                $jp_dueDate_id = 'JPD000000001';
            }
            
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $currentDate = date("Y-m-d H:i:s");
            // Calculate the due date 5 days from now
            $dueDate = date("Y-m-d H:i:s", strtotime($currentDate . "+5 weekdays"));

            $sql3 = "INSERT INTO due_date (jp_dueDate_id, dueDate, dueDate_duration, payment_id, status) VALUES ('$jp_dueDate_id', '$dueDate', '5', (SELECT payment_id FROM payment WHERE jp_payment_id = '$jp_payment_id' LIMIT 1), 1);";
            $result3 = mysqli_query($conn, $sql3); 
            

            // Fetch the current maximum value from the 'confirmation' table
            $query = "SELECT MAX(CAST(SUBSTRING(jp_confirmation_id, 9) AS UNSIGNED)) AS max_id FROM confirmation";
            $result = $conn->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                $maxId = $row['max_id'];
                // Increment the maximum value
                $nextId = $maxId + 1;

                // Format the next ID with leading zeros
                $jp_confirmation_id = 'JPC' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
            } else {
                // Default value if there are no existing records
                $jp_confirmation_id = 'JPC0001';
            }
            $sql4 = "INSERT INTO confirmation (jp_confirmation_id, currentLocation, pickUpLocation_id, confirmationStatus_id) VALUES ('$jp_confirmation_id','-', $location,3);";
            $result4 = mysqli_query($conn, $sql4); 

            

            // Fetch the current maximum value from the 'item_management' table
            $query = "SELECT MAX(CAST(SUBSTRING(resit_id, 9) AS UNSIGNED)) AS max_id FROM item_management";
            $result = $conn->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                $maxId = $row['max_id'];
                // Increment the maximum value
                $nextId = $maxId + 1;

                // Format the next ID with leading zeros
                $resit_id = 'JPR' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
            } else {
                // Default value if there are no existing records
                $resit_id = 'JPR000000001';
            }
            $sql4 = "INSERT INTO item_management (user_id, item_id, dueDate_id, confirmation_id, resit_id, JomPick_ID, registerDate, ready_id, availability_id)
                        VALUES (
                            $user_id,
                            (SELECT item_id FROM item WHERE jp_item_id = '$jp_item_id' LIMIT 1),
                            (SELECT dueDate_id FROM due_date WHERE jp_dueDate_id = '$jp_dueDate_id' LIMIT 1),
                            (SELECT confirmation_id FROM confirmation WHERE jp_confirmation_id = '$jp_confirmation_id' LIMIT 1),
                            '$resit_id',
                            '$jpid',
                            '$currentDate',
                            2,
                            1
                        );";
                        
            $result4 = mysqli_query($conn, $sql4); 
            
            // Send OneSignal Notification

            // Fetch the item_id of the newly inserted item
            $getItemIdQuery = "SELECT item_id FROM item WHERE jp_item_id = ?";
            $stmt = $conn->prepare($getItemIdQuery);
            $stmt->bind_param("s", $jp_item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $item_id = $row['item_id'];
            } else {
                // Handle the case where item_id is not found
                header("Location: ../jompick-register.php?error=Item ID not found");
                exit();
            }


            // Fetch the item details based on item_id
            $getItemDetailsQuery = "SELECT name, trackingNumber, itemType_id FROM item WHERE item_id = ?";
            $stmt = $conn->prepare($getItemDetailsQuery);
            $stmt->bind_param("i", $item_id); // Assuming item_id is an integer
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $itemName = $row['name'];
                $trackingNumber = $row['trackingNumber'];
                $itemType_id = $row['itemType_id'];
            } else {
                // Handle the case where item details are not found
                header("Location: ../jompick-register.php?error=Item details not found");
                exit();
            }   

            // Fetch Pick Up Location Id
            $getPickUpLocationQuery = "SELECT item_management.item_id, item_management.confirmation_id, 
            confirmation.pickUpLocation_id, pickup_location.address
            FROM item_management
            INNER JOIN item ON item_management.item_id = item.item_id
            INNER JOIN confirmation ON item_management.confirmation_id = confirmation.confirmation_id
            INNER JOIN pickup_location ON confirmation.pickUpLocation_id = pickup_location.pickUpLocation_id
            WHERE item_management.item_id = ?";

                $stmt = $conn->prepare($getPickUpLocationQuery);
                $stmt->bind_param("i", $item_id); // Assuming item_id is an integer
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $pickUpLocation = $row['address'];
                } else {
                    // Handle the case where item details are not found
                    header("Location: ../jompick-register.php?Pick up location not found");
                    exit();
                }   



            // Fetch the user's external user ID (player ID) based on the JomPick ID
            $getExternalUserIDQuery = "SELECT user_id  FROM user WHERE JomPick_ID = ?";
            $stmt = $conn->prepare($getExternalUserIDQuery);
            $stmt->bind_param("s", $jpid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the player ID
                $row = $result->fetch_assoc();
                $externalUserId = $row['user_id'];
            

                // Send push notification using OneSignal
                $api_url = 'https://onesignal.com/api/v1/notifications';
                $app_id = 'b470c9ed-9cfe-4ae2-8aef-63d312e6bbe8';
                $api_key = 'MGE5N2Q4YWMtMjQ5Mi00ODNlLWIyMTUtZWU5ZjFlZDA5MGE1';

                // Prepare data for the OneSignal API call

                $external_user_ids = array(strval($externalUserId)); // strval convert user_id to string and use the fetched external user ID
                $contents = array( 'en' => "Your item '{$itemName}' has arrived at '{$pickUpLocation}' today. Open JomPick app for more details.'");

                $data = array(
                    'app_id' => $app_id,
                    'include_external_user_ids' => $external_user_ids,
                    'contents' => $contents,
                );

                // Convert data to JSON
                $data_string = json_encode($data);

                // Set up cURL for making the request
                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . $api_key,
                ));
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute cURL request
                $result = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    echo 'Curl error: ' . curl_error($ch);
                    // Handle the error as needed
                } else {
                    // Close cURL session
                    curl_close($ch);
                }

                // Check if the notification was sent successfully
                $responseData = json_decode($result, true);
                if (isset($responseData['id'])) {
                    // Notification sent successfully
                    header("Location: ../jompick-register.php?success=Item Registered Successfully!");
                    exit();
                } else {
                    // Error sending notification
                    header("Location: ../jompick-register.php?error=Notification Error");
                    exit();
                }
            } else {
                // User not found, handle the error
                header("Location: ../jompick-register.php?error=User not found");
                exit();
            }   
            } else {
                header("Location: ../jompick-register.php?error=Error Adding Item");
        }

        $stmt->close();

    } else {
        header("Location: ../jompick-register.php?error=Error Uploading Image File");
    }

    $conn->close();
}
?>
