<?php
    session_start();

    // Check if user is logged in, redirect to login page if not
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit;
    }

    $page = 'index.php'; 
    $screen_name = 'Dashboard';
    $location_id = $_SESSION['jp_location_id'];
    $userid = $_SESSION["id"];
    $userrole= $_SESSION["role_id"];


    include 'api/db_connection.php'; // Include your database connection

    $sql_location = "SELECT name FROM pickup_location WHERE pickupLocation_id = '$location_id'";
    $result_location = $conn->query($sql_location);

    if ($result_location->num_rows > 0) {
        $location_row = $result_location->fetch_assoc();
        $userlocation = $location_row["name"];
    }

    
    if ($location_id == 1) {
        $location_condition = '!= ' . $location_id;
    } else {
        $location_condition = '= ' . $location_id;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head -->
    <?php include 'includecode/head.php' ?>
    <!-- Head -->
</head>

<style>
</style>

<body id="page-top">

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <?php 
                            $sql = "SELECT SUM(p.paymentAmount) AS totalPaymentAmount
                            FROM item_management AS im
                            JOIN due_date AS dd ON im.dueDate_id = dd.dueDate_id
                            JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                            JOIN payment AS p ON dd.payment_id = p.payment_id where paymentStatus_id = 3 
                            and c.pickUpLocation_id $location_condition;";
                    
                            // Execute the query
                            $result = $conn->query($sql);
                            
                            // Check if the query was successful
                            if ($result) {
                                // Fetch the result
                                $row = $result->fetch_assoc();
                                $totalPaymentAmount = $row['totalPaymentAmount'];
                            
                                // Output the result
                                //echo "Total Payment Amount: $totalPaymentAmount";
                            
                                // Free the result set
                                $result->free_result();
                            } else {
                                // Handle the case where the query failed
                                echo "Error: " . $conn->error;
                            }

                            $totalPaymentAmount = $totalPaymentAmount ?? '0.00';
                        ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Penalty <?php echo "$userlocation";?>)</div>
                                            <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo "RM" . $totalPaymentAmount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            // SQL query
                            $sql = "SELECT COUNT(*) AS totalCustomers FROM user WHERE role_id IN (4, 5, 6)";

                            // Execute the query
                            $result = $conn->query($sql);

                            // Check if the query was successful
                            if ($result) {
                                // Fetch the result
                                $row = $result->fetch_assoc();
                                $totalCustomers = $row['totalCustomers'];

                                // Output the result
                                //echo "Total Customers: $totalCustomers";

                                // Free the result set
                                $result->free_result();
                            } else {
                                // Handle the case where the query failed
                                echo "Error: " . $conn->error;
                            }

                        ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Client</div>
                                            <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $totalCustomers; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                            // SQL query
                            $sql = "SELECT COUNT(*) AS totalItems FROM item_management where user_id = $user_id";

                            // Execute the query
                            $result = $conn->query($sql);

                            // Check if the query was successful
                            if ($result) {
                                // Fetch the result
                                $row = $result->fetch_assoc();
                                $totalItems = $row['totalItems'];

                                // Output the result
                                //echo "Total Customers: $totalCustomers";

                                // Free the result set
                                $result->free_result();
                            } else {
                                // Handle the case where the query failed
                                echo "Error: " . $conn->error;
                            }
                        ?>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">My Total Item Register
                                            </div>
                                            <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $totalItems; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            // SQL query
                            $sql = "SELECT COUNT(*) AS totalPicked
                            FROM item_management AS im
                            JOIN confirmation AS c ON im.confirmation_id = c.confirmation_id
                            WHERE c.confirmationStatus_id = 2 and c.pickUpLocation_id $location_condition;";

                            // Execute the query
                            $result = $conn->query($sql);

                            // Check if the query was successful
                            if ($result) {
                            // Fetch the result
                            $row = $result->fetch_assoc();
                            $totalPicked = $row['totalPicked'];

                            // Output the result
                            //echo "Total Picked: $totalPicked";

                            // Free the result set
                            $result->free_result();
                            } else {
                            // Handle the case where the query failed
                            echo "Error: " . $conn->error;
                            }

                        ?>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Items Picked (<?php echo "$userlocation"?>)</div>
                                            <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $totalPicked; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row  current date = . date('F Y')  and YEAR(im.registerDate) = $currentYear
                                                                    AND MONTH(im.registerDate) = $currentMonth-->

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-xl-6 col-md-6">
                                    <div style="float:left;margin-top:5px;"><h5 class="m-0 font-weight-bold text-primary">Item To Be Prepared <?php echo "For $userlocation" ; ?></h5></div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <div style="float:right;"><a href="jompick-list.php" class="btn btn-primary btn-sm" style="margin-top:3px;"><i class="fa fa-clipboard-list"></i>&nbsp;&nbsp;Begin Tasks</a></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <?Php
                                    date_default_timezone_set('Asia/Kuala_Lumpur');
                                    $currentDate = date("Y-m-d");
                                    // Get the current year and month
                                    $currentYear = date('Y');
                                    $currentMonth = date('m');

                                            //set semula tanpa filtering
                                        $sql = "SELECT
                                            im.*, DATE_FORMAT(im.registerDate, '%d-%m-%Y') AS registerDate,
                                            u.userName AS username, -- replace 'username' with the actual column name in the user table
                                            i.name AS itemname, -- replace 'item_name' with the actual column name in the item table
                                            i.image AS image, 
                                            c.confirmationStatus_id AS status, -- replace 'confirmation_status' with the actual column name in the confirmation table
                                            c.currentLocation AS location,
                                            c.pickUpDuration As pduration,
                                            DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s') As cDate,
                                            c.confirmation_id As c_id,
                                            pl.name AS pickuplocation
                                            FROM
                                            item_management im
                                            LEFT JOIN user u ON im.user_id = u.user_id
                                            LEFT JOIN item i ON im.item_id = i.item_id
                                            LEFT JOIN due_date dd ON im.dueDate_id = dd.dueDate_id
                                            LEFT JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                                            LEFT JOIN pickup_location pl ON c.pickUpLocation_id = pl.pickupLocation_id 
                                            WHERE itemManagement_id !='' and c.pickUpLocation_id $location_condition and im.availability_id = 1 
                                            and c.confirmationStatus_id = 1 and im.ready_id = 2
                                            ORDER BY 
                                                CASE 
                                                    WHEN c.confirmationStatus_id !='' THEN c.confirmationStatus_id
                                                    ELSE c.pickUpDate
                                                END ASC,
                                                CASE 
                                                    WHEN c.confirmationStatus_id = 1 THEN c.pickUpDate
                                                    ELSE c.confirmationStatus_id
                                                END ASC;"; 

                                            $result = mysqli_query($conn, $sql);
                                            //print $sql;
                                    ?>
                                    <thead>
                                        <tr>
                                            <th width="25px">Num.</th>
                                            <th>Resit ID</th>
                                            <th>Date</th>
                                            <th>PIC Item</th>
                                            <th>JomPick ID</th>
                                            <th>Owner Name</th>
                                            <th>Pickup Location</th>
                                            <th>Pickup Duration</th>
                                            <th>Pickup Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Num.</th>
                                            <th>Resit ID</th>
                                            <th>Date</th>
                                            <th>Manager</th>
                                            <th>JomPick ID</th>
                                            <th>Owner Name</th>
                                            <th>Pickup Location</th>
                                            <th>Pickup Duration</th>
                                            <th>Pickup Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $x=1;
                                            while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){

                                            $resit = $row['resit_id'];
                                            $jompickid = $row['JomPick_ID'];
                                            $registerdate = $row['registerDate'];
                                            $username = $row['username'];
                                            $pickuplocation = $row['pickuplocation'];
                                            $itemname = $row['itemname'];
                                            $image = $row['image'];
                                            $status = $row['status'];
                                            $location = $row['location'];
                                            $pduration = $row['pduration'];
                                            $ready = $row['ready_id'];
                                            $cDate = $row['cDate'];
                                            $c_id = $row['c_id'];

                                            if($ready == 1 ){
                                                $readytext = 'Ready';
                                                $colorr = 'success'; 
                                                $iconr = 'check-square';
                                            }
                                            if($ready == 2 ){
                                                $readytext = 'Unready';
                                                $colorr = 'danger'; 
                                                $iconr = 'square';
                                            }
                                                
                                            if ($status == '1') {
                                                $status = 'Pick now';
                                                $color = 'orange';    
                                            } else if ($status == '2') {
                                                $status = 'Picked';
                                                $color = 'green';  
                                            } else if ($status == '3') {
                                                $status = 'Pending';
                                                $color = 'red';  
                                            }
                                           
                                            $imageId = "smallImage_" . $x;

                                            ?>
                                            <tr>
                                                <td style="vertical-align: middle;" ><?php echo $x;?></td>
                                                <td style="vertical-align: middle;"><?php echo $resit; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $registerdate; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $username; ?></td>
                                                <td style="vertical-align: middle;"><?php echo strtoupper($jompickid); ?></td>
                                                <td style="vertical-align: middle;"><?php echo $itemname; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $pickuplocation; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $pduration; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $cDate; ?></td>
                                                <td style="vertical-align: middle;color:<?php echo $color; ?>;"><?php echo $status; ?></td>
                                            </tr>
                                        <?php $x++;} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
<?php
$conn->close();
?>

</body>

</html>