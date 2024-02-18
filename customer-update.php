
<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'customer-update.php'; 
$screen_name = 'Customer Update';

$jpid = isset($_GET['jpid']) ? $_GET['jpid'] : '';

$roleid = isset($_SESSION["role_id"]) ? $_SESSION["role_id"] : ''; 

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

                <h1 class="h3 mb-4 text-gray-800">Customer's Profile</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Profile</h6>
                        </div>
                        <form method="post" action="function/update-customer.php?jpid=<?php echo $jpid;?>" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="card-body">
                                <?php
                           
                                $sql = "SELECT u.*, r.rolename FROM user u JOIN role r ON u.role_id = r.role_id WHERE JomPick_ID = '$jpid'"; 
                                $result = mysqli_query($conn, $sql);
                                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

                                $username = $row['userName'];
                                $password = $row['password'];
                                $phoneNumber = $row['phoneNumber'];
                                $icNumber = $row['icNumber'];
                                $emailAddress = $row['emailAddress'];
                                $fullName = $row['fullName'];
                                $role_id = $row['role_id'];
                                $image = $row['image'];

                                if($role_id == 1){
                                    $role_id = "Admin";
                                }else if($role_id == 2){
                                    $role_id = "Manager";
                                }else if($role_id == 3){
                                    $role_id = "Staff";
                                }
                            ?>
                            <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username:</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="fullname">Full Name:</label>
                                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullName; ?>" required onkeyup="validateFullName()">
                                            <span id="fullnameError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="phonenumber">Phone Number:</label>
                                            <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="<?php echo $phoneNumber; ?>" required onkeyup="validatePhoneNumber()">
                                            <span id="phonenumberError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="icnumber">IC Number:</label>
                                            <input type="text" class="form-control" id="icnumber" name="icnumber" value="<?php echo $icNumber; ?>" required onkeyup="validateICNumber()">
                                            <span id="icnumberError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="email">E-Mail:</label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $emailAddress; ?>" required onkeyup="validateEmail()">
                                            <span id="emailError" class="errorss"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">    
                                            <label for="image">Image:</label>
                                            <div class="image-container">
                                                <img id="preview-image" src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode($image), ENT_QUOTES, 'UTF-8'); ?>" />
                                            </div>
                                            <?php if($roleid == 1 ){echo '<input type="file" id="image" name="image" class="form-control" onchange="previewImage(this)">';} ?>
                                                
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
                                        <div class="small text-white"><a href="customer-list.php" class="btn btn-primary btn-sm">Back</a></div>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <?php if($roleid == 1 ){echo '<div style="float:right;"><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</button></div>';} ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <?php 
                        if ($roleid == 1) {
                            echo '<div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Update Password</h6>
                                    </div>
                                    <form method="post" id="fpass" name="fpass" action="function/update-pascust.php?jpid=' . $jpid . '" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group">
                                                        <label for="oldpassword">Old Password:</label>
                                                        <input type="password" class="form-control" id="oldpassword" name="oldpassword" onkeyup="validateOldPassword()"/>
                                                        <span id="oldPasswordError" class="errorss"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group">
                                                        <label for="newpassword">New Password:</label>
                                                        <input type="password" class="form-control" id="newpassword" name="newpassword" onkeyup="validateNewPassword()"/>
                                                        <span id="newPasswordError" class="errorss"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="form-group">
                                                        <label for="cnewpassword">Confirm New Password:</label>
                                                        <input type="password" class="form-control" id="cnewpassword" name="cnewpassword" onkeyup="validateConfirmPassword()"/>
                                                        <span id="confirmPasswordError" class="errorss"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer py-3">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6">
                                                    <div class="small text-white"><a href="customer-list.php" class="btn btn-primary btn-sm">Back</a></div>
                                                </div>
                                                <div class="col-xl-6 col-md-6">
                                                    <div style="float:right;"><button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>';
                        }
                        ?>
                    
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
        function validateFullName() {
            var fullNameInput = document.getElementById('fullname');
            var fullNameError = document.getElementById('fullnameError');

            if (fullNameInput.value.trim() === '') {
                fullNameError.innerHTML = 'Full Name is required';
            } else {
                fullNameError.innerHTML = '';
            }
        }

        function validatePhoneNumber() {
            var phoneNumberInput = document.getElementById('phonenumber');
            var phoneNumberError = document.getElementById('phonenumberError');

            var phoneNumberPattern = /^\d{10,11}$/; // Simple pattern for 10-digit phone number

            if (!phoneNumberPattern.test(phoneNumberInput.value.trim())) {
                phoneNumberError.innerHTML = 'Enter a valid 10 or 11 digit phone number';
            } else {
                phoneNumberError.innerHTML = '';
            }
        }

        function validateICNumber() {
            var icNumberInput = document.getElementById('icnumber');
            var icNumberError = document.getElementById('icnumberError');

            var icNumberPattern = /^\d{12}$/; // Simple pattern for 12-digit IC number

            if (!icNumberPattern.test(icNumberInput.value.trim())) {
                icNumberError.innerHTML = 'Enter a valid 12-digit IC number';
            } else {
                icNumberError.innerHTML = '';
            }
        }

        function validateEmail() {
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('emailError');

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple pattern for email address

            if (!emailPattern.test(emailInput.value.trim())) {
                emailError.innerHTML = 'Enter a valid email address';
            } else {
                emailError.innerHTML = '';
            }
        }

        function validateForm() {
            validateFullName();
            validatePhoneNumber();
            validateICNumber();
            validateEmail();

            var fullNameError = document.getElementById('fullnameError').innerHTML;
            var phoneNumberError = document.getElementById('phonenumberError').innerHTML;
            var icNumberError = document.getElementById('icnumberError').innerHTML;
            var emailError = document.getElementById('emailError').innerHTML;

            if (fullNameError === '' && phoneNumberError === '' && icNumberError === '' && emailError === '') {
                return true; // Submit the form
            } else {
                return false; // Stop form submission
            }
        }
    </script>

    <script>
        function validateOldPassword() {
            var oldPassword = document.getElementById('oldpassword').value;
            var oldPasswordError = document.getElementById('oldPasswordError');
            // Clear previous error messages
            oldPasswordError.textContent = '';
            // Validate that old password is not empty
            if (oldPassword.trim() === '') {
                oldPasswordError.textContent = 'Old Password cannot be empty.';
            }
        }

        function validateNewPassword() {
            // Validation logic for new password
            var newPassword = document.getElementById('newpassword').value;
            var newPasswordError = document.getElementById('newPasswordError');

            // Add your validation rules here
            var hasUpperCase = /[A-Z]/.test(newPassword);
            var hasLowerCase = /[a-z]/.test(newPassword);
            var hasNumber = /\d/.test(newPassword);
            var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(newPassword);

            if (newPassword.length < 6 || !hasUpperCase || !hasLowerCase || !hasNumber || !hasSpecialChar) {
                newPasswordError.textContent = 'Password must be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            } else {
                newPasswordError.textContent = '';
            }
        }

        function validateConfirmPassword() {
            // Validation logic for confirm password
            var newPassword = document.getElementById('newpassword').value;
            var confirmPassword = document.getElementById('cnewpassword').value;
            var confirmPasswordError = document.getElementById('confirmPasswordError');

            // Add your validation rules here
            if (newPassword !== confirmPassword) {
                confirmPasswordError.textContent = 'Passwords do not match';
            } else {
                confirmPasswordError.textContent = '';
            }
        }
    </script>

    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->


</body>
</html>