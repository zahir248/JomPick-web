<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'location-update.php'; 
$screen_name = 'Update Location';


$location_id = isset($_GET['location']) ? $_GET['location'] : '';

include 'api/db_connection.php'; // Include your database connection

// Ensure $location_id is not empty and is a valid integer
if (!empty($location_id) && is_numeric($location_id)) {
    $stmt = $conn->prepare("SELECT address, image, name FROM pickup_location WHERE pickupLocation_id = ?");
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $stmt->bind_result($location_address, $image, $locationname);

    if ($stmt->fetch()) {
        $image_data = base64_encode($image);
        $image_src = "data:image/jpeg;base64," . $image_data;
    } else {
        echo "Location and image not found.";
    }

    $stmt->close();
} else {
    echo "Invalid or missing location_id parameter.";
}


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
                    <h1 class="h3 mb-4 text-gray-800">Update Location</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Location</h6>
                        </div>
                            <form method="post" action="function/update-location.php?location=<?php echo $location_id?>" enctype="multipart/form-data" onsubmit="return validateForm()">                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="locations">Location Name:</label>
                                            <input type="text" class="form-control" id="locations" name="locations" value="<?php echo htmlspecialchars($locationname);?>" onkeyup="validateLocationName()">
                                            <span id="locationNameError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="regAddress">Location Address:</label>
                                            <input type="text" class="form-control" id="regAddress" name="regAddress" value="<?php echo htmlspecialchars($location_address);?>" onkeyup="validateLocationAddress()">
                                            <span id="locationAddressError" class="errorss"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">    
                                            <label for="image">Choose Image:</label>
                                            <div class="image-container">
                                                <img id="preview-image" src="<?php echo isset($image_src) ? $image_src : 'upload/JomPick_logo2.jpg'; ?>" alt="Image">
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
                                                        preview.src = "placeholder.jpg"; // Default image or placeholder
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-3">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div class="small text-white">
                                            <a href="location-list.php?location=" class="btn btn-primary btn-sm">Back</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <div style="float:right;">
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</button>
                                        </div>
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

    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->

    <script>
        function validateLocationName() {
            var locationName = document.getElementById('locations').value;
            var locationNameError = document.getElementById('locationNameError');
            if (locationName.trim() === '') {
                locationNameError.innerHTML = 'Location Name is required';
            } else {
                locationNameError.innerHTML = '';
            }
        }
        function validateLocationAddress() {
            var locationAddress = document.getElementById('regAddress').value;
            var locationAddressError = document.getElementById('locationAddressError');
            if (locationAddress.trim() === '') {
                locationAddressError.innerHTML = 'Location Address is required';
            } else {
                locationAddressError.innerHTML = '';
            }
        }


        function validateForm() {
            // Validate location name and image before submitting the form
            validateLocationName();
            validateLocationAddress();

            // Check if there are any validation errors
            var locationNameError = document.getElementById('locationNameError').innerHTML;
            var locationAddressError = document.getElementById('locationAddressError').innerHTML;

            if (locationNameError === '' && locationAddressError === '') {
                return true; // Submit the form
            } else {
                return false; // Stop form submission
            }
        }
    </script>
    


</body>
</html>