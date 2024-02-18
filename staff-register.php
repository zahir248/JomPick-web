<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'staff-register.php'; 
$screen_name = 'Staff Register';

include 'api/db_connection.php'; // Include your database connection

$userrole= $_SESSION["role_id"];

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
                    <h1 class="h3 mb-4 text-gray-800">Add New Staff</h1>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add New Staff</h6>
                            </div>
                            <form method="post" action="function/add-staff.php" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="username">Username:</label>
                                                <input type="text" class="form-control" id="username" name="username" onkeyup="validateUserName()">
                                                <span id="usernameError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="password">Password:</label>
                                                <input type="password" class="form-control" id="password" name="password" onkeyup="validateNewPassword()"/>
                                                <span id="newPasswordError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="cpassword">Confirm Password:</label>
                                                <input type="password" class="form-control" id="cpassword" name="cpassword" onkeyup="validateConfirmPassword()"/>
                                                <span id="confirmPasswordError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="phonenumber">Phone Number:</label>
                                                <input type="text" class="form-control" id="phonenumber" name="phonenumber" onkeyup="validatePhoneNumber()">
                                                <span id="phonenumberError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="icnumber">IC Number:</label>
                                                <input type="text" class="form-control" id="icnumber" name="icnumber" onkeyup="validateICNumber()">
                                                <span id="icnumberError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="email">E-Mail</label>
                                                <input type="text" class="form-control" id="email" name="email" onkeyup="validateEmail()">
                                                <span id="emailError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                <label for="fullname">Full Name</label>
                                                <input type="text" class = "form-control" id="fullname" name="fullname"  onkeyup="validateFullName()">
                                                <span id="fullnameError" class="errorss"></span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class="form-group">
                                                    <label for="image">Select Image's Location:</label>
                                                    <input type="file" class="form-control" id="image" name="image" required>
                                            </div>
                                        </div>
                                        <?php
                                            if ($userrole == '1') {
                                                echo '<div class="col-xl-3 col-md-6">
                                                        <div class="form-group">
                                                            <label for="userpic">Person Incharge</label>
                                                            <select class="custom-select" id="userpic" name="userpic" required>
                                                                <option value="">Please Select</option>';
                                                                
                                                                // Assuming $conn is your database connection
                                                                $query = "SELECT user_id, userName FROM user WHERE role_id = 2;";
                                                                $result = $conn->query($query);

                                                                if ($result && $result->num_rows > 0) {
                                                                    while ($row1 = $result->fetch_assoc()) {
                                                                        $userid = $row1['user_id'];
                                                                        $userName = $row1['userName'];
                                                                        echo "<option value='$userid'>$userName</option>";
                                                                    }
                                                                } else {
                                                                    echo "<option value='' disabled>No locations available</option>";
                                                                }

                                                                // Close the result set
                                                                $result->close();
                                                                
                                                echo '</select>
                                                    </div>
                                                </div>';
                                            }
                                            ?>
                                    </div>
                                </div>
                                    <div class="card-footer py-3" >
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6">
                                                <div class="small text-white"><a href="manager-list.php?location=" class="btn btn-primary btn-sm">View Staffs</a></div>
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
        function validateUserName() {
            var usernameInput = document.getElementById('username');
            var usernameError = document.getElementById('usernameError');

            if (usernameInput.value.trim() === '') {
                usernameError.innerHTML = 'Username is required';
            } else {
                usernameError.innerHTML = '';
            }
        }

        function validateNewPassword() {
            // Validation logic for new password
            var newPassword = document.getElementById('password').value;
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
            var newPassword = document.getElementById('password').value;
            var confirmPassword = document.getElementById('cpassword').value;
            var confirmPasswordError = document.getElementById('confirmPasswordError');

            // Add your validation rules here
            if (newPassword !== confirmPassword) {
                confirmPasswordError.textContent = 'Passwords do not match';
            } else {
                confirmPasswordError.textContent = '';
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

        function validateFullName() {
            var fullNameInput = document.getElementById('fullname');
            var fullNameError = document.getElementById('fullnameError');

            if (fullNameInput.value.trim() === '') {
                fullNameError.innerHTML = 'Full Name is required';
            } else {
                fullNameError.innerHTML = '';
            }
        }

        function validateImage() {
            var imageInput = document.getElementById('image');
            var imageError = document.getElementById('imageError');

            if (!imageInput.files.length) {
                imageError.innerHTML = 'Select an image';
            } else {
                imageError.innerHTML = '';
            }
        }

        function validatePersonInCharge() {
            var userpicInput = document.getElementById('userpic');
            var userpicError = document.getElementById('userpicError');

            if (userpicInput.value === '') {
                userpicError.innerHTML = 'Select a person in charge';
            } else {
                userpicError.innerHTML = '';
            }
        }

        function validateForm() {
            validateUserName()
            validateNewPassword();
            validateConfirmPassword();
            validatePhoneNumber();
            validateICNumber();
            validateEmail();
            validateFullName();

            var usernameError = document.getElementById('usernameError').innerHTML;
            var phoneNumberError = document.getElementById('phonenumberError').innerHTML;
            var icNumberError = document.getElementById('icnumberError').innerHTML;
            var emailError = document.getElementById('emailError').innerHTML;
            var fullNameError = document.getElementById('fullnameError').innerHTML;
            var newPasswordError = document.getElementById('newPasswordError').innerHTML;
            var confirmPasswordError = document.getElementById('confirmPasswordError').innerHTML;

            if (usernameError === '' && phoneNumberError === '' && icNumberError === '' && emailError === '' && fullNameError === '' && newPasswordError === ''&& confirmPasswordError === '') {
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