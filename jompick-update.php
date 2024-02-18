<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'jompick-update.php'; 
$screen_name = 'Update Jompick';


$userid = $_SESSION["id"];
$userrole= $_SESSION["role_id"];

$resitid = isset($_GET['resitid']) ? $_GET['resitid'] : '';

include 'api/db_connection.php'; // Include your database connection

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Head -->
    <?php include 'includecode/head.php' ?>
    <!-- Head -->
</head>
<style>
    label{
        margin-bottom:5px;
    }
    input{
        margin-bottom:5px;
    }
    input::file-selector-button {
    height:40px;
    color: grey;
    border: none;
    margin-left:-15px;
    margin-top:-7px;
    padding-left:15px;
    padding-right:15px;
    margin-right:15px;
      /* Add pointer cursor on hover */
    cursor: pointer;
    }

    /* Style the file input button on hover */
    input[type="file"]::file-selector-button:hover {
        background-color: #d0d0d0; /* Add a background color or any other hover effect */
    }

    /* Style the file input button on active/pressed state */
    input[type="file"]::file-selector-button:active {
        background-color: #e0e0e0; /* Add a background color or any other active/pressed effect */
    }

    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center; /* For older browsers */
        border: 1px solid #ccc; /* Add border styles here */
        padding: 10px;
        margin-bottom:10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .image-container img {
        width: 100px;
        height: 100px;
    }
    /* The modal styles */
    .modala {
        display: none;
        position: fixed;
        z-index: 4;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        cursor: pointer;
    }

    .modala-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 75%; /* Increase the max-width to make the image larger */
        max-height: 50%; /* Increase the max-height to make the image larger */
        width: 750px; /* Set width to auto to maintain aspect ratio */
        height: 500px; /* Set height to auto to maintain aspect ratio */
    }

    .closea {
        color: #fff;
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    #alert-containers {
        position: fixed;
        margin-top: 85px;
        top: 0;
        right: 25px;
        width: 80%;
        max-width: 450px; /* Adjust the maximum width as needed */
        z-index: 5; /* Ensure it appears above other elements */
        opacity: 1;
        transition: opacity 1s ease-in-out;
    }
    
    .errorss {
        color: red;
    }
</style>

    
<body id="page-top">
    <?php
        // Check if 'error' parameter is set in the URL
        if (isset($_GET['error'])) {
            // Display an error message with the value of the 'error' parameter
            echo '<div id="alert-containers" class="alert alert-danger" role="alert">' . $_GET['error'] . '</div>';
            
        }

        // Check if 'error' parameter is set in the URL
        if (isset($_GET['success'])) {
            // Display an error message with the value of the 'error' parameter
            echo '<div id="alert-containers" class="alert alert-success" role="success">' . $_GET['success'] . '</div>';
            
        }
    
    ?>

    <script>
        // Wait for the DOM to be ready
        document.addEventListener("DOMContentLoaded", function() {
            // Set a timeout to hide the error message after 5 seconds
            setTimeout(function() {
                var alertContainer = document.getElementById('alert-containers');
                if (alertContainer) {
                    // Hide the error message by setting display to 'none'
                    alertContainer.style.display = 'none';
                }
            }, 3000); // 3000 milliseconds = 3 seconds
        });
    </script>
    <!-- The modal -->
    <div id="imageModal" class="modala" onclick="closeModal()">
        <span class="closea" onclick="closeModal()">&times;</span>
        <img class="modala-content" id="largeImage">
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Side Nav -->
        <?php include 'function/navigation/sidenav.php' ?>
        <!-- Side Nav -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'function/navigation/topnav.php' ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Jompick's Details</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Jompick</h6>
                        </div>
                        <form method="post" action="function/update-jompick.php?resitid=<?php echo $resitid;?>" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="card-body">
                                <?php
                           
                                $sql = "SELECT 
                                        im.resit_id as resit, 
                                        im.JomPick_ID as jpid,
                                        im.ready_id as readystatus, 
                                        i.jp_item_id as jpitemid,
                                        i.itemType_id as itemtypeid,
                                        i.name as ownername,
                                        i.image as itemimage,
                                        i.trackingNumber as tnum,
                                        c.jp_confirmation_id as jpconfirmationid,
                                        c.confirmationStatus_id as status,
                                        c.pickUpLocation_id as pickUpLocation,
                                        DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s') As cDate,
                                        pl.name as pladdress,
                                        DATE_FORMAT(dd.dueDate, '%d-%m-%Y') as ddDate
                                        FROM item_management im 
                                        JOIN item i ON im.item_id = i.item_id 
                                        JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                                        JOIN due_date dd ON im.dueDate_id = dd.dueDate_id
                                        JOIN pickup_location pl ON c.pickUpLocation_id = pl.pickupLocation_id 
                                        WHERE im.resit_id = '$resitid' LIMIT 1"; 

                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                                $resit = $row['resit'];
                                $jpid = $row['jpid'];
                                $jpitemid = $row['jpitemid'];
                                $jpconfirmationid = $row['jpconfirmationid'];
                                $ownername = $row['ownername'];
                                $itemtypeid = $row['itemtypeid'];
                                $tnum = $row['tnum'];
                                $pickUpLocation = $row['pickUpLocation'];
                                $pladdress = $row['pladdress'];
                                $itemimage = $row['itemimage'];
                                $status = $row['status'];
                                $readystatus = $row['readystatus'];
                                $ddDate = $row['ddDate'];
                                $cDate  = $row['cDate'];
                                $ddDate = DateTime::createFromFormat('d-m-Y', $ddDate)->format('Y-m-d');
                                
                                
                            ?>
                            <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="resit">Resit ID: </label>
                                            <input type="text" class="form-control" id="resit" name="resit" value="<?php echo $resit; ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="jpid">JomPick ID: </label>
                                            <input type="text" class="form-control" id="jpid" name="jpid" value="<?php echo $jpid; ?>" required onkeyup="validateJomPickID()">
                                            <span id="jpidError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="dueDate">Due Date: </label>
                                            <input type="date" class="form-control" id="dueDate" name="dueDate" value="<?php echo $ddDate; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="cDate">Pick-Up Date: </label>
                                            <input type="text" class = "form-control" id="cDate" name="cDate" value="<?php echo $cDate; ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="ownername">Item Name: </label>
                                            <input type="text" class="form-control" id="ownername" name="ownername" value="<?php echo $ownername; ?>" required onkeyup="validateItemName()">
                                            <span id="itemNameError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <?php 
                                        $selecteddoc = " ";
                                        $selectedpar = " ";
                                        if($itemtypeid == 1){
                                            $selecteddoc = 'selected';
                                        }
                                        if($itemtypeid == 2){
                                            $selectedpar = 'selected';
                                        }
                                    ?>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type:</label><br/>
                                            <select class="custom-select" id="type" name="type" onchange="validateType()">
                                                <option value="1" <?php echo $selecteddoc; ?>>Document</option>
                                                <option value="2" <?php echo $selectedpar; ?>>Parcel</option>
                                                <!-- Add more options as needed -->
                                            </select>
                                            <span id="typeError" class="errorss"></span> <!-- Error message container -->
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="tnum">Tracking Number: </label>
                                            <input type="text" class="form-control" id="tnum" name="tnum" value="<?php echo $tnum; ?>" required onkeyup="validateTrackingNumber()">
                                            <span id="trackingNumberError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="locations">Pick-Up Location:</label>
                                            <select class="custom-select" id="locations" name="locations" required>
                                                <?php  if ($userrole == '1'){
                                                // Assuming $conn is your database connection
                                                $query = "SELECT pickupLocation_id, name FROM pickup_location WHERE pickupLocation_id != 1 and availability_id = 1;";
                                                $result = $conn->query($query);
                                                echo "<option value='$pickUpLocation'>$pladdress</option>";
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $location_id = $row['pickupLocation_id'];
                                                        $address = $row['name'];
                                                        echo "<option value='$location_id'>$address</option>";
                                                    }
                                                } else {
                                                    echo "<option value='' disabled>No locations available</option>";
                                                }

                                                
                                                } else if ($userrole == '2' || $userrole == '3') {
                                    
                                                    $sql9 = "SELECT jp_location_id FROM user WHERE user_id = $userid;";
                                                    $result9 = mysqli_query($conn, $sql9);
                                                    $row9 = mysqli_fetch_array($result9, MYSQLI_ASSOC);
                                                    $location = $row9['jp_location_id'];
                                                    $result9->close();
                                    
                                                    $sql8 = "SELECT name FROM pickup_location WHERE pickupLocation_id = $location;";
                                                    $result8 = mysqli_query($conn, $sql8);
                                                    $row8 = mysqli_fetch_array($result8, MYSQLI_ASSOC);
                                                    $address = $row8['name'];
                                                    $result8->close();

                                                    echo "<option value='$location_id'>$address</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label><br/>
                                            <select class="custom-select" id="status" name="status" onchange="validateStatus()">
                                                <option value="">Please Select</option>
                                                <option value="1" <?php echo ($status === '1') ? 'selected' : ''; ?>>Pick Now</option>
                                                <option value="2" <?php echo ($status === '2') ? 'selected' : ''; ?>>Picked</option>
                                                <option value="3" <?php echo ($status === '3') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="4" <?php echo ($status === '4') ? 'selected' : ''; ?>>Disposed</option>
                                                <!-- Add more options as needed -->
                                            </select>
                                            <span id="statusError" class="errorss"></span> <!-- Error message container -->
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">    
                                            <label for="image">Choose Your Image: </label>
                                            <div class="image-container">
                                                <img id="preview-image" src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode($itemimage), ENT_QUOTES, 'UTF-8'); ?>" onclick="showLargeImage('preview-image')" />
                                            </div>
                                            <input type="file" id="image" name="image" class="form-control" onchange="previewImage(this)">
                                            <script>
                                                function previewImage(input) {
                                                    var preview = document.getElementById('preview-image');
                                                    var file = input.files[0];
                                                    var reader = new FileReader();

                                                    reader.onloadend = function () {
                                                        preview.src = reader.result;
                                                    };

                                                    if (file) {
                                                        reader.readAsDataURL(file);
                                                    } else {
                                                        preview.src = "upload/JomPick_logo2.jpg"; // Default image or placeholder
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-3" >
                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div class="small text-white"><a href="jompick-list.php" class="btn btn-primary btn-sm">Back</a></div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div style="float:right;"><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</button></div>
                                    </div>
                                </div>
                            </div>
                        <form>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'includecode/copyright.php'?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        function showLargeImage(imageId) {
        // Get the small image and modal
        var smallImage = document.getElementById(imageId);
        var modal = document.getElementById('imageModal');
        var largeImage = document.getElementById('largeImage');

        // Set the source of the large image to the clicked small image
        largeImage.src = smallImage.src;

        // Display the modal
        modal.style.display = 'block';
    }

    function closeModal() {
        // Hide the modal when the close button is clicked
        document.getElementById('imageModal').style.display = 'none';
    }
    </script>

    <script>
        function validateJomPickID() {
            var jpid = document.getElementById('jpid').value;
            var jpidError = document.getElementById('jpidError');

            if (jpid.trim() === '') {
                jpidError.innerHTML = 'JomPick ID is required';
            } else {
                jpidError.innerHTML = '';
            }
        }

        function validateItemName() {
            var itemName = document.getElementById('ownername').value;
            var itemNameError = document.getElementById('itemNameError');

            if (itemName.trim() === '') {
                itemNameError.innerHTML = 'Item Name is required';
            } else {
                itemNameError.innerHTML = '';
            }
        }

        function validateType() {
            var typeDropdown = document.getElementById('type');
            var typeError = document.getElementById('typeError');

            if (typeDropdown.value === '') {
                typeError.innerHTML = 'Please select a type';
            } else {
                typeError.innerHTML = '';
            }
        }

        function validateLocation() {
            var locationDropdown = document.getElementById('locations');
            var locationError = document.getElementById('locationError');

            if (locationDropdown.value === '') {
                locationError.innerHTML = 'Please select a location';
            } else {
                locationError.innerHTML = '';
            }
        }

        function validateStatus() {
            var statusDropdown = document.getElementById('status');
            var statusError = document.getElementById('statusError');

            if (statusDropdown.value === '') {
                statusError.innerHTML = 'Please select a status';
            } else {
                statusError.innerHTML = '';
            }
        }

        function validateTrackingNumber() {
            var trackingNumber = document.getElementById('tnum').value;
            var trackingNumberError = document.getElementById('trackingNumberError');

            if (trackingNumber.trim() === '') {
                trackingNumberError.innerHTML = 'Tracking Number is required';
            } else {
                trackingNumberError.innerHTML = '';
            }
        }

        function validateImage() {
            var imageError = document.getElementById('imageError');
            var input = document.getElementById('image');
            var file = input.files[0];

            if (!file) {
                imageError.innerHTML = 'Please select an image';
            } else {
                imageError.innerHTML = '';
                previewImage(input);
            }
        }

        function validateForm() {
            // Call all individual validation functions
            validateJomPickID();
            validateItemName();
            validateItemType();
            validateLocation();
            validateStatus();
            validateTrackingNumber();
            validateImage();  // Call without passing input

            // Check if there are any validation errors
            var jpidError = document.getElementById('jpidError').innerHTML;
            var itemNameError = document.getElementById('itemNameError').innerHTML;
            var typeError = document.getElementById('typeError').innerHTML;
            var locationError = document.getElementById('locationError').innerHTML;
            var statusError = document.getElementById('statusError').innerHTML;
            var trackingNumberError = document.getElementById('trackingNumberError').innerHTML;
            var imageError = document.getElementById('imageError').innerHTML;

            if (jpidError === '' && itemNameError === '' && typeError  === '' && locationError === '' && statusError === '' && trackingNumberError === '' && imageError === '') {
                return true; // Submit the form
            } else {
                return false; // Stop form submission
            }
        }
    </script>
    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->


</body>
</html>