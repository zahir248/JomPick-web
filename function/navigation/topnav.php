<?php

    // Check if user is logged in, redirect to login page if not
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit;
    }

    $page = 'index.php'; 
    $screen_name = 'Dashboard';

    include 'api/db_connection.php'; // Include your database connection

    // Fetch user's name from the database
    $user_id = $_SESSION["id"];
    $role_id = $_SESSION["role_id"];
    $jp_location_id = $_SESSION["jp_location_id"];
    $sql_user = "SELECT userName, image FROM user WHERE user_id = '$user_id'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $user_row = $result_user->fetch_assoc();
        $user_name = $user_row["userName"];
        $image = $user_row['image'];
    }

?>



<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <!-- <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div> -->
        </div>
    </form>

    <!-- Topbar Navbar -->
                    
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <!-- <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            Dropdown - Messages
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div> -->
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <?php
                $totalRowsQuery = "SELECT COUNT(*) AS totalRows FROM item_management im
                    JOIN confirmation c ON im.confirmation_id = c.confirmation_id
                    JOIN item i ON im.item_id = i.item_id
                    WHERE c.confirmationStatus_id = 1 and im.ready_id = 2";

                $totalRowsResult = mysqli_query($conn, $totalRowsQuery);

                // Fetch the total number of rows
                $totalRows = mysqli_fetch_assoc($totalRowsResult)['totalRows'];
                ?>
                <?php if ($totalRows != 0) {
                    echo "<span class='badge badge-danger badge-counter'>$totalRows</span>";
                } ?>
            </a>
            <!-- Dropdown - Alerts -->
            <div id="resultContainer" class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <?php

                $your_db_connection = $conn;
                // Your SQL query
                $sql123 = "SELECT im.JomPick_ID as jpidclient, c.confirmationStatus_id AS status, c.pickUpDate, i.name
                        FROM item_management im
                        JOIN confirmation c ON im.confirmation_id = c.confirmation_id
                        JOIN item i ON im.item_id = i.item_id
                        WHERE c.confirmationStatus_id = 1 and im.ready_id = 2 LIMIT 3";

                // Execute the query
                $result123 = mysqli_query($your_db_connection, $sql123);

                // Check for query execution errors
                if (!$result123) {
                    die("Query failed: " . mysqli_error($your_db_connection));
                }
                ?>

                <!-- Loop through the fetched data and generate HTML -->
                <?php
                while ($row = mysqli_fetch_assoc($result123)) {
                    echo '<a class="dropdown-item d-flex align-items-center" href="jompick-list.php">
                            <div class="mr-3">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">' . $row['pickUpDate'] . '</div>
                                <span class="font-weight-bold">' . $row['jpidclient'] . '</span>
                            </div>
                        </a>';
                }
                ?>

                <?php 
                    $messagenoti = "Show all";
                    if ($totalRows == 0){
                        $messagenoti = "You don't have any notifications.";
                    }
                ?>
                <a class="dropdown-item text-center small text-gray-500" href="jompick-list.php"><?php echo $messagenoti;?></a>
            </div>

            <script>
                     // Function to refresh the content using AJAX
                    function refreshContent() {
                        // Perform AJAX request
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                // Update the content of the result container
                                document.getElementById('resultContainer').innerHTML = this.responseText;
                            }
                        };
                        xhttp.open('GET', 'topnav.php', true);
                        xhttp.send();
                    }

                    // Call the refresh function every 5 seconds
                    setInterval(function () {
                        refreshContent();
                    }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        </li>

       
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php 
                        $sql_role = "SELECT rolename FROM role WHERE role_id = '$role_id'";
                        $result_role = $conn->query($sql_role);

                        if ($result_role->num_rows > 0) {
                            $role_row = $result_role->fetch_assoc();
                            $rolename = $role_row["rolename"];
                        }

                        $sql_location = "SELECT name FROM pickup_location WHERE pickupLocation_id = '$jp_location_id'";
                        $result_location = $conn->query($sql_location);

                        if ($result_location->num_rows > 0) {
                            $location_row = $result_location->fetch_assoc();
                            $location = $location_row["name"];
                        }

                        if($role_id=='1'){$role_id="Admin";}
                        if($role_id=='2'){$role_id="Manager";}
                        if($role_id=='3'){$role_id="Staff";}
                        echo strtoupper($user_name . ' - ' . $rolename .' - @' . $location);?>
                    </span>&nbsp;&nbsp;
                <!-- <img class="img-profile rounded-circle"
                    src="img/undraw_profile.svg"> -->

                <img  class="img-profile rounded-circle" src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode($image), ENT_QUOTES, 'UTF-8'); ?>" />
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="user-profile.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" onclick="logout()">Logout</a>
                </div>
            </div>
        </div>
    </div>

<script>
    function logout() {
            window.location.href = "function/logout.php"; 
    }
</script>