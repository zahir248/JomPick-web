<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'jompick-register.php'; 
$screen_name = 'JomPick Register';

$userid = $_SESSION["id"];
$userrole= $_SESSION["role_id"];

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
    }

    .image-container img {
        width: 100px;
        height: 100px;
    }

    #alert-containers {
        position: fixed;
        margin-top: 85px;
        top: 0;
        right: 25px;
        width: 80%;
        max-width: 450px; /* Adjust the maximum width as needed */
        z-index: 5; /* Ensure it appears above other elements */
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
                    <h1 class="h3 mb-4 text-gray-800">Add New JomPick</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add New JomPick</h6>
                        </div>
                            <form method="post" id="yourFormId" action="function/add-jompick.php" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="jpid">JomPick ID:</label>
                                                <input type="text" class="form-control" id="jpid" name="jpid" value="<?php echo isset($_GET['jpid']) ? $_GET['jpid'] : ''; ?>" onkeyup="validateJomPickID()">
                                                <span id="jpidError" class="errorss"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="ownername">Item Name:</label>
                                                <input type="text" class="form-control" id="ownername" name="ownername" value="<?php echo isset($_GET['ownername']) ? $_GET['ownername'] : ''; ?>" onkeyup="validateItemName()">
                                                <span id="itemNameError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label style="margin-bottom:15px;" >Type:</label><br>
                                                <input style="margin-left:10px;" type="radio" name="itemType_id" value="1" id="documentType" <?php echo (isset($_GET['itemType_id']) && $_GET['itemType_id'] == 1) ? 'checked' : ''; ?> onchange="validateItemType()">
                                                <label for="documentType">Document:</label>
                                                &nbsp;&nbsp;<input type="radio" name="itemType_id" value="2" id="parcelType" <?php echo (isset($_GET['itemType_id']) && $_GET['itemType_id'] == 2) ? 'checked' : ''; ?> onchange="validateItemType()">
                                                <label for="parcelType">Parcel</label>
                                                <br/>&nbsp;&nbsp;<span id="itemTypeError" class="errorss"></span>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6" id="trackingNumberSection" style="display: none;">
                                            <div class="form-group">
                                                <label for="tracknum">Tracking Number:</label>
                                                <input type="text" class="form-control" id="tracknum" name="tracknum" value="<?php echo isset($_GET['tracknum']) ? $_GET['tracknum'] : ''; ?>">
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                // Hide tracking number section on page load
                                                document.getElementById('trackingNumberSection').style.display = 'none';

                                                // Show/hide tracking number section based on radio button selection
                                                var parcelTypeRadio = document.getElementById('parcelType');
                                                var documentTypeRadio = document.getElementById('documentType');
                                                var trackingNumberSection = document.getElementById('trackingNumberSection');

                                                parcelTypeRadio.addEventListener('change', function () {
                                                    if (parcelTypeRadio.checked) {
                                                        trackingNumberSection.style.display = 'block';
                                                    } else {
                                                        trackingNumberSection.style.display = 'none';
                                                    }
                                                });

                                                documentTypeRadio.addEventListener('change', function () {
                                                    if (documentTypeRadio.checked) {
                                                        trackingNumberSection.style.display = 'none';
                                                    }
                                                });
                                            });
                                        </script>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="locations">Location:</label>
                                                <select class="custom-select" id="locations" name="locations" onchange="validateLocation()">
                                                    <?php if ($userrole == '1') {
                                                        // Assuming $conn is your database connection
                                                        $query = "SELECT pickupLocation_id, name FROM pickup_location WHERE pickupLocation_id != 1 and availability_id = 1;";
                                                        $result = $conn->query($query);
                                                        echo "<option value=''>Please Select</option>";
                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = $result->fetch_assoc()) {
                                                                $location_id = $row['pickupLocation_id'];
                                                                $address = $row['name'];
                                                                echo "<option value='$location_id'";
                                                                echo (isset($_GET['locations']) && $_GET['locations'] === $location_id) ? 'selected' : '';
                                                                echo ">$address</option>";
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

                                                        echo "<option value='$location' selected >$address</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <span id="locationError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">    
                                                <label for="image">Take Item Image:</label>
                                                <div class="image-container">
                                                    <img id="preview-image" src="<?php echo isset($image_src) ? $image_src : 'upload/JomPick_logo2.jpg'; ?>" alt="Image">
                                                </div>
                                                <input type="file" id="image" name="image" class="form-control" value="<?php echo isset($_GET['image']) ? $_GET['image'] : ''; ?>" onchange="validateImage(this)">
                                                <span id="imageError" class="errorss"></span>
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
                                                            preview.src = "placeholder.jpg"; // Default image or placeholder
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
                                            <div class="small text-white"><a href="jompick-list.php?location=" class="btn btn-primary btn-sm">View JomPicks</a></div>
                                        </div>
                                        <div class="col-xl-6 col-md-6">
                                            <div style="float:right;"><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</button></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        function validateItemType() {
            var documentTypeRadio = document.getElementById('documentType');
            var parcelTypeRadio = document.getElementById('parcelType');
            var itemTypeError = document.getElementById('itemTypeError');

            if (!documentTypeRadio.checked && !parcelTypeRadio.checked) {
                itemTypeError.innerHTML = 'Please select an item type';
            } else {
                itemTypeError.innerHTML = '';
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

        document.getElementById('location').addEventListener('change', function () {
            validateLocation();
        });

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
            validateImage();  // Call without passing input

            // Check if there are any validation errors
            var jpidError = document.getElementById('jpidError').innerHTML;
            var itemNameError = document.getElementById('itemNameError').innerHTML;
            var itemTypeError = document.getElementById('itemTypeError').innerHTML;
            var locationError = document.getElementById('locationError').innerHTML;
            var imageError = document.getElementById('imageError').innerHTML;

            if (jpidError === '' && itemNameError === '' && itemTypeError === '' && locationError === '' && imageError === '') {
                return true; // Submit the form
            } else {
                return false; // Stop form submission
            }
        }
    </script>


    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->


    <?php 
        // Clear the 'error' parameter from the URL using JavaScript
        echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
    ?>

</body>
</html>