<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'jompick-penalty-list.php'; 
$screen_name = 'JomPick Penalty List';
$location_id = $_SESSION['jp_location_id'];
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
                    <h1 class="h3 mb-4 text-gray-800">Penalty's List</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search</h6>
                        </div>
                        <form method="GET" id="myForm" action="jompick-penalty-list.php" enctype="multipart/form-data">
                            <div class="card-body">
                            <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="resitid">Resit ID:</label><br/>
                                                <input type="text" class="form-control" id="resitid" name="resitid" value="<?php echo isset($_GET['resitid']) ? $_GET['resitid'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="jompickid">JomPick ID:</label><br/>
                                                <input type="text" class="form-control" id="jompickid" name="jompickid" value="<?php echo isset($_GET['jompickid']) ? $_GET['jompickid'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="pnstartdate">Start Pick-Up Date:</label><br/>
                                                <input type="date" class="form-control" id="pnstartdate" name="pnstartdate" value="<?php echo isset($_GET['pnstartdate']) ? $_GET['pnstartdate'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="pnenddate">End Pick-Up Date:</label><br/>
                                                <input type="date" class="form-control" id="pnenddate" name="pnenddate" value="<?php echo isset($_GET['pnenddate']) ? $_GET['pnenddate'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="itemmanager">Manager:</label><br/>
                                                <input type="text" class="form-control" id="itemmanager" name="itemmanager" value="<?php echo isset($_GET['itemmanager']) ? $_GET['itemmanager'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="itemname">Owner Name:</label><br/>
                                                <input type="text" class="form-control" id="itemname" name="itemname" value="<?php echo isset($_GET['itemname']) ? $_GET['itemname'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label><br/>
                                            <select class="custom-select" id="status" name="status">
                                                <option value="">Please Select</option>
                                                <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Ongoing</option>
                                                <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] === '2') ? 'selected' : ''; ?>>Early</option>
                                                <option value="3" <?php echo (isset($_GET['status']) && $_GET['status'] === '3') ? 'selected' : ''; ?>>Late</option>
                                                <!-- Add more options as needed -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="location">Pick-up Location:</label>
                                            <select class="custom-select" id="location" name="location" value="<?php echo isset($_GET['status']) ? $_GET['status'] : ''; ?>">
                                                <?php  if ($userrole == '1'){
                                                // Assuming $conn is your database connection
                                                $query = "SELECT pickupLocation_id, name FROM pickup_location WHERE pickupLocation_id != 1 and availability_id = 1;";
                                                $result = $conn->query($query);
                                                echo "<option value=''>Please Select</option>";
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $location_idd = $row['pickupLocation_id'];
                                                        $addresss = $row['name'];
                                                        echo "<option value='$location_idd'";
                                                        echo (isset($_GET['location']) && $_GET['location'] === $location_idd) ? 'selected' : '';
                                                        echo ">$addresss</option>";
                                                    }
                                                } else {
                                                    echo "<option value='' disabled>No locations available</option>";
                                                }

                                                
                                                } else if ($userrole == '2' || $userrole == '3') {
                                    
                                                    $sql9 = "SELECT jp_location_id FROM user WHERE user_id = $userid;";
                                                    $result9 = mysqli_query($conn, $sql9);
                                                    $row9 = mysqli_fetch_array($result9, MYSQLI_ASSOC);
                                                    $locationidd = $row9['jp_location_id'];
                                                    $result9->close();
                                    
                                                    $sql8 = "SELECT name FROM pickup_location WHERE pickupLocation_id = $locationidd;";
                                                    $result8 = mysqli_query($conn, $sql8);
                                                    $row8 = mysqli_fetch_array($result8, MYSQLI_ASSOC);
                                                    $address = $row8['name'];
                                                    $result8->close();

                                                    echo "<option value='$location_idd'";
                                                    echo (isset($_GET['location']) && $_GET['location'] === $location_idd) ? 'selected' : '';
                                                    echo ">$address</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-footer py-3" >
                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div><button type="submit" onclick="resetForm()" class="btn btn-primary btn-sm" name="carian" value="carian" id="carian">Reset</button></div>
                                    </div>
                                    <script type="text/javascript">
                                        function resetForm() {
                                            document.forms["myForm"]["resitid"].value = '';
                                            document.forms["myForm"]["jompickid"].value = '';
                                            document.forms["myForm"]["pnstartdate"].value = '';
                                            document.forms["myForm"]["pnenddate"].value = '';
                                            document.forms["myForm"]["itemmanager"].value = '';
                                            document.forms["myForm"]["itemname"].value = '';
                                            document.forms["myForm"]["status"].value = '';
                                            document.forms["myForm"]["type"].value = '';
                                            document.forms["myForm"]["location"].value = '';
                                        }
                                    </script>
                                    <div class="col-xl-6 col-md-6">
                                        <div style="float:right;"><button type="submit" class="btn btn-primary btn-sm" name="carian" value="carian" id="carian"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button></div>
                                    </div>
                                </div>
                            </div>
                        <form>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">JomPick List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <?Php

                                    if ($location_id == 1) {
                                        $location_condition = '!= ' . $location_id;
                                    } else {
                                        $location_condition = '= ' . $location_id;
                                    }

                                    $sql = "SELECT
                                        im.*,
                                        u.userName AS username, 
                                        i.name AS itemname, 
                                        DATE_FORMAT(dd.dueDate, '%d-%m-%Y') AS duedate,
                                        dd.status AS status,
                                        dd.dueDate_id AS dd_id,
                                        p.paymentStatus_id AS statuspayment,
                                        p.paymentAmount AS totalamount,
                                        p.payment_id AS p_id,
                                        pl.name AS address,
                                        DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s') AS cDate,
                                        c.confirmationStatus_id as cStatus
                                        FROM
                                        item_management im
                                        LEFT JOIN user u ON im.user_id = u.user_id
                                        LEFT JOIN item i ON im.item_id = i.item_id
                                        LEFT JOIN due_date dd ON im.dueDate_id = dd.dueDate_id
                                        LEFT JOIN payment p ON dd.payment_id = p.payment_id 
                                        LEFT JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                                        LEFT JOIN pickup_location pl ON c.pickupLocation_id = pl.pickupLocation_id
                                        WHERE itemManagement_id != '' AND c.pickupLocation_id $location_condition";

                                        //filtering listing
                                        if (isset($_GET['carian'])) {
                                            $resitid=$_GET['resitid'];
                                            $jompickid=$_GET['jompickid'];
                                            $itemmanager=$_GET['itemmanager'];
                                            $itemname=$_GET['itemname'];
                                            $status=$_GET['status'];
                                            $location=$_GET['location'];
                                            $pnstartdate = isset($_GET['pnstartdate']) ? $_GET['pnstartdate'] : '';
                                            $pnenddate = isset($_GET['pnenddate']) ? $_GET['pnenddate'] : '';

                                            if (!empty($pnstartdate)) {
                                                $pnstartdate = date('y-m-d', strtotime($pnstartdate));
                                            }

                                            if (!empty($pnenddate)) {
                                                $pnenddate = date('y-m-d', strtotime($pnenddate));
                                            }


                                        if($resitid!=""){
                                                $sql= $sql . " and im.resit_id = '$resitid'";
                                                $statement = $sql;
                                            } 
                                            if($jompickid!=""){
                                                $sql= $sql . " and im.JomPick_ID like '%$jompickid%'";
                                                $statement = $sql;
                                            }

                                            if (!empty($pnstartdate) && !empty($pnenddate)) {
                                                // Assuming you want to filter by a date range
                                                $sql .= " AND c.pickUpDate BETWEEN '$pnstartdate' AND '$pnenddate'";
                                            } elseif (!empty($pnstartdate)) {
                                                // Assuming you want to filter by a start date
                                                $sql .= " AND c.pickUpDate >= '$pnstartdate'";
                                            } elseif (!empty($pnenddate)) {
                                                // Assuming you want to filter by an end date
                                                $sql .= " AND c.pickUpDate <= '$pnenddate'";
                                            }

                                            if($itemmanager!=""){
                                                $sql= $sql . " and u.userName like '%$itemmanager%'";
                                                $statement = $sql;
                                            }

                                            if($itemname!=""){
                                                $sql= $sql . " and i.name like '%$itemname%'";
                                                $statement = $sql;
                                            }
                                            
                                            if($status!=""){
                                                $sql= $sql . " and dd.status = $status";
                                                $statement = $sql;
                                            }

                                            if($location !=""){
                                                $sql= $sql . " and pl.pickupLocation_id = $location";
                                                $statement = $sql;
                                            }
                                        

                                            //$statement = $sql . " ORDER BY ord_ID DESC ";
                                            $rec_count = mysqli_num_rows($result);
                                                
                                            $sql= $sql . " ORDER BY statuspayment desc;";          
                                            $statement = $sql;
                                            //print $sql;
                                            $result = mysqli_query($conn, $sql);

                                        }else{
                                                //set semula tanpa filtering
                                                $sql = "SELECT
                                                im.*,
                                                u.userName AS username, 
                                                i.name AS itemname, 
                                                DATE_FORMAT(dd.dueDate, '%d-%m-%Y') AS duedate,
                                                dd.status AS status,
                                                dd.dueDate_id AS dd_id,
                                                p.paymentStatus_id AS statuspayment,
                                                p.payment_id AS p_id,
                                                p.paymentAmount AS totalamount,
                                                pl.name AS address,
                                                DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s')  as cDate,
                                                c.confirmationStatus_id as cStatus
                                                FROM
                                                item_management im
                                                LEFT JOIN user u ON im.user_id = u.user_id
                                                LEFT JOIN item i ON im.item_id = i.item_id
                                                LEFT JOIN due_date dd ON im.dueDate_id = dd.dueDate_id
                                                LEFT JOIN payment p ON dd.payment_id = p.payment_id 
                                                LEFT JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                                                LEFT JOIN pickup_location pl ON c.pickupLocation_id = pl.pickupLocation_id
                                                WHERE itemManagement_id != '' and c.pickupLocation_id $location_condition 
                                                ORDER BY statuspayment desc; "; 

                                            $result = mysqli_query($conn, $sql);
                                            //print $sql;
                                        }

                                
                                    ?>
                                    <thead>
                                        <tr>
                                            <th width="25px">Num.</th>
                                            <th>Resit ID</th>
                                            <th>JomPick ID</th>
                                            <th>Item Manager</th>
                                            <th>Item Name</th>
                                            <th>Pickup Location</th>
                                            <th width="80px">Due Date</th>
                                            <th width="80px">Pick-Up Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th width="75px">Actions Payment</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Num.</th>
                                            <th>Resit ID</th>
                                            <th>JomPick ID</th>
                                            <th>Item Manager</th>
                                            <th>Item Name</th>
                                            <th>Pickup Location</th>
                                            <th>Due Date</th>
                                            <th>Pick-Up Date</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Actions Payment</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php 
                                        $x = 1;

                                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $resit = $row['resit_id'];
                                            $jompickid = $row['JomPick_ID'];
                                            $username = $row['username'];
                                            $itemname = $row['itemname'];
                                            $duedate = $row['duedate'];
                                            $status = $row['status'];
                                            $statuspayment = $row['statuspayment'];
                                            $totalamount = $row['totalamount'];
                                            $plocation = $row['address'];
                                            $button = '';
                                            $pickdate = $row['cDate'];
                                            $status = $row['status'];
                                            $pickDateTime = ($row['cDate'] != null) ? new DateTime($row['cDate']) : null;
                                            $dueDateTime = new DateTime($row['duedate']);
                                            $dd_id = $row['dd_id'];
                                            $p_id = $row['p_id'];

                                            $cStatus = $row['cStatus'];

                                            date_default_timezone_set('Asia/Kuala_Lumpur');
                                            $currentDate = new DateTime(); // Get the current date as a DateTime object

                                            $ddDate = new DateTime($row['duedate']);  // Assuming $ddDate is a DateTime object


                                            if ($status == '1' || ($status == '3' && $cStatus == '1')  || ($status == '3' && $cStatus == '3')) {
                                                if ($currentDate > $dueDateTime) {
                                                    $upddd = "UPDATE due_date 
                                                                SET status = 3
                                                                WHERE dueDate_id = '$dd_id';";
                                                    $updddResult = mysqli_query($conn, $upddd);

                                                    if (($status == '1' && $statuspayment == 1) || ($status == '1' && $statuspayment == 4)|| ($status == '3' && $cStatus == '1')  || ($status == '3' && $cStatus == '3')) {
                                                        $statuspayment = "4";
                                                        $lateDays = $currentDate->diff($dueDateTime)->days;
                                                        $latePaymentRate = 1; // Adjust this rate according to your requirement
                                                        $penaltyAmount = $lateDays * $latePaymentRate;
                                                        $maxPenaltyAmount = 7;
                                                        // Limit the penalty amount to the maximum allowed
                                                        $penaltyAmount = min($penaltyAmount, $maxPenaltyAmount);

                                                        $updp = "UPDATE payment 
                                                                    SET paymentStatus_id = 4, paymentAmount = $penaltyAmount
                                                                    WHERE payment_id = '$p_id';";
                                                        $updpResult = mysqli_query($conn, $updp);
                                                    }

                                                    $status = "3";
                                                }
                                            }

                                            
                                            if ($status == '3' || $statuspayment === '4') {

                                            if ($status == '1') {
                                                $statusname = 'Pending';
                                                $color1 = 'orange';
                                            } else if ($status == '2') {
                                                $statusname = 'Early';
                                                $color1 = 'green';
                                            } else if ($status === '3') {
                                                $statusname = 'Late';
                                                $color1 = 'red';
                                            }

                                            if ($statuspayment == '1') {
                                                $statuspaymentname = 'Pending';
                                                $colorr = 'warning';
                                                $iconr = '';
                                                $color = 'orange';
                                                $button = '<a href="#" class="btn btn-warning btn-sm" style="margin-top:3px; color:white;"><div class="fa-solid fa-circle-play"></div> Ongoing </a>';
                                            } elseif ($statuspayment == '2') {
                                                $statuspaymentname = 'Pass';
                                                $nicknamestatus = "Pass";
                                                $color = 'blue';
                                                $colorr = 'info';
                                                $iconr = 'check-square';
                                                $button = '<a href="javascript:void(0);" class="btn btn-info btn-sm" style="margin-top:3px; color:white;"><i class="fa-regular fa-circle-stop"></i> Pass </a>';
                                            } elseif ($statuspayment == '3') {
                                                $statuspaymentname = 'Paid';
                                                $nicknamestatus = "Paid";
                                                $color = 'green';
                                                $colorr = 'success';
                                                $iconr = 'check-square';
                                                $button = '<a href="#" class="btn btn-success btn-sm" style="margin-top:3px; color:white;"><span class="fa-regular fa-circle-check"></span> Paid </a>';
                                            } elseif ($statuspayment == '4') {
                                                $statuspaymentname = 'Pay';
                                                $nicknamestatus = "Unpaid";
                                                $color = 'red';
                                                $colorr = 'danger';
                                                $iconr = 'square';
                                                $button = '<a href="#" class="btn btn-danger btn-sm" style="margin-top:3px; color:white;"><i class="fa-regular fa-circle-xmark"></i> Pay </a>';
                                            }

                                        ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $resit; ?></td>
                                                <td><?php echo $jompickid; ?></td>
                                                <td><?php echo $username; ?></td>
                                                <td><?php echo $itemname; ?></td>
                                                <td><?php echo $plocation; ?></td>
                                                <td><?php echo $duedate; ?></td>
                                                <td><?php echo $pickdate; ?></td>
                                                <td><div style="color:<?php echo $color; ?>;"><?php echo $nicknamestatus; ?></div></td>
                                                <td><?php echo "RM" . $totalamount; ?></td>
                                                <td>
                                                <a class="btn btn-<?php echo $colorr; ?> btn-sm" style="margin-top: 3px;" data-toggle="modal" data-target="#updateModal<?php echo $p_id; ?>">
                                                    <i class="fas fa-<?php echo $iconr; ?>" style="color: <?php echo $colorr; ?>;"></i> &nbsp;<?php echo $statuspaymentname; ?>
                                                </a>

                                                <!-- Update Modal -->
                                                <div class="modal fade" id="updateModal<?php echo $p_id; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateModalLabel">Update Penalty</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Add your update confirmation message here -->
                                                                <p>Are you sure you want to pay the penalty payment with ID: <?php echo $resit; ?>?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="function/update-penalty.php?p_id=<?php echo $p_id; ?>&statuspayment=<?php echo $statuspayment; ?>" class="btn btn-<?php echo $colorr; ?>">Update</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        <?php $x++;
                                            }
                                        }
                                        ?>
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