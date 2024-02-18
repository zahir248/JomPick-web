
<?php
    $screen_name="Login";
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Head -->
    <?php include 'includecode/head.php' ?>
    <!-- Head -->
</head>

<style>
    #error-containers {
        position: fixed;
        top: 0;
        width: 100%;/* Adjust the maximum width as needed */
        z-index: 5; /* Ensure it appears above other elements */
    }
</style>

<body > 

<?php
// Check if 'error' parameter is set in the URL
if (isset($_GET['error'])) {
    // Display an error message with the value of the 'error' parameter
    echo '<div id="error-containers" class="alert alert-danger text-center" role="alert">' . $_GET['error'] . '</div>';
    
    // Clear the 'error' parameter from the URL using JavaScript
    echo '<script>history.replaceState({}, document.title, "' . $_SERVER['PHP_SELF'] . '");</script>';
}
?>

<div class="container mt-5">

    <div class="row justify-content-center" style="margin-top:100px;">
        <div class="col-md-6">
            <div class="row justify-content-center" style="padding-bottom: 50px">
                <img src="assets/JomPick_logo1.jpg" alt="Logo" style="width:150px;height:150px;">
            </div>
            <div class="card">
                <div class="card-body" >
                    <!-- Create a form to submit login credentials to 'login.php' -->
                    <form method="post" action="function/login.php">
                        <label for="username">Username</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user" style="padding:0px 4px 0px;"></i> <!-- Envelope icon for email field -->
                                </span>
                            </div>
                            <input type="username" class="form-control" id="username" name="username" placeholder="Username" required>
                        </div>
                        <label for="password">Password</label>
                        <div class="input-group mb-3" style="padding-bottom: 10px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    &#128274; <!-- Lock icon for password field -->
                                </span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-blue btn-block" style="background-color: #6D87EC; color: white;">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->

</body>
</html>