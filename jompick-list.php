<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'jompick-list.php'; 
$screen_name = 'JomPick List';
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
    .image-container {
    text-align: center;
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
        /* width: 750px; Set width to auto to maintain aspect ratio */
        /* height: 500px; Set height to auto to maintain aspect ratio */
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
</style>
    
<body id="page-top">
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
                    <h1 class="h3 mb-4 text-gray-800">JomPick's List</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search</h6>
                        </div>
                        <form method="GET" id="myForm" action="jompick-list.php" enctype="multipart/form-data">
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
                                                <label for="startdate">Start Register Date:</label><br/>
                                                <input type="date" class="form-control" id="startdate" name="startdate" value="<?php echo isset($_GET['startdate']) ? $_GET['startdate'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="enddate">End Register Date:</label><br/>
                                                <input type="date" class="form-control" id="enddate" name="enddate" value="<?php echo isset($_GET['enddate']) ? $_GET['enddate'] : ''; ?>">
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
                                                <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Pick Now</option>
                                                <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] === '2') ? 'selected' : ''; ?>>Picked</option>
                                                <option value="3" <?php echo (isset($_GET['status']) && $_GET['status'] === '3') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="4" <?php echo (isset($_GET['status']) && $_GET['status'] === '4') ? 'selected' : ''; ?>>Disposed</option>
                                                <!-- Add more options as needed -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="setup">Setup:</label><br/>
                                            <select class="custom-select" id="setup" name="setup">
                                                <option value="">Please Select</option>
                                                <option value="1" <?php echo (isset($_GET['setup']) && $_GET['setup'] === '1') ? 'selected' : ''; ?>>Ready</option>
                                                <option value="2" <?php echo (isset($_GET['setup']) && $_GET['setup'] === '2') ? 'selected' : ''; ?>>Unready</option>
                                                <!-- Add more options as needed -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type:</label><br/>
                                            <select class="custom-select" id="type" name="type"  >
                                                <option value="">Please Select</option>
                                                <option value="1" <?php echo (isset($_GET['type']) && $_GET['type'] === '1') ? 'selected' : ''; ?>>Document</option>
                                                <option value="2" <?php echo (isset($_GET['type']) && $_GET['type'] === '2') ? 'selected' : ''; ?>>Parcel</option>
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
                                            document.forms["myForm"]["startdate"].value = '';
                                            document.forms["myForm"]["enddate"].value = '';
                                            document.forms["myForm"]["pnstartdate"].value = '';
                                            document.forms["myForm"]["pnenddate"].value = '';
                                            document.forms["myForm"]["itemmanager"].value = '';
                                            document.forms["myForm"]["itemname"].value = '';
                                            document.forms["myForm"]["status"].value = '';
                                            document.forms["myForm"]["setup"].value = '';
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
                                            im.*, DATE_FORMAT(im.registerDate, '%d-%m-%Y') AS registerDate,
                                            u.userName AS username,
                                            i.name AS itemname, 
                                            i.image AS image, 
                                            c.confirmationStatus_id AS status, 
                                            c.currentLocation AS location,
                                            c.pickUpDuration As pduration,
                                            c.confirmation_id As c_id,
                                            DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s') As pdate,
                                            pl.name AS pickuplocation,
                                            DATE_FORMAT(dd.dueDate, '%d-%m-%Y %H:%i:%s') As ddDate
                                            FROM
                                            item_management im
                                            LEFT JOIN user u ON im.user_id = u.user_id
                                            LEFT JOIN item i ON im.item_id = i.item_id
                                            LEFT JOIN due_date dd ON im.dueDate_id = dd.dueDate_id
                                            LEFT JOIN confirmation c ON im.confirmation_id = c.confirmation_id 
                                            LEFT JOIN pickup_location pl ON c.pickUpLocation_id = pl.pickupLocation_id 
                                            WHERE itemManagement_id !='' and c.pickUpLocation_id $location_condition and im.availability_id = 1 "; 

                                        //filtering listing
                                        if (isset($_GET['carian'])) {
                                            $resitid=$_GET['resitid'];
                                            $jompickid=$_GET['jompickid'];
                                            $itemmanager=$_GET['itemmanager'];
                                            $itemname=$_GET['itemname'];
                                            $status=$_GET['status'];
                                            $location=$_GET['location'];
                                            $setup=$_GET['setup'];

                                            $startdate = isset($_GET['startdate']) ? $_GET['startdate'] : '';
                                            $enddate = isset($_GET['enddate']) ? $_GET['enddate'] : '';

                                            $pnstartdate = isset($_GET['pnstartdate']) ? $_GET['pnstartdate'] : '';
                                            $pnenddate = isset($_GET['pnenddate']) ? $_GET['pnenddate'] : '';

                                            // Format the input dates to "yy-mm-dd" format
                                            if (!empty($startdate)) {
                                                $startdate = date('y-m-d', strtotime($startdate));
                                            }

                                            if (!empty($enddate)) {
                                                $enddate = date('y-m-d', strtotime($enddate));
                                            }

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

                                        if (!empty($startdate) && !empty($enddate)) {
                                            // Assuming you want to filter by a date range
                                            $sql .= " AND registerDate BETWEEN '$startdate' AND '$enddate'";
                                        }
                                        if ($startdate != "") {
                                            // Assuming you want to filter by start date
                                            $sql = $sql . " AND registerDate >= '$startdate'";
                                        }
                                        if ($enddate != "") {
                                            // Assuming you want to filter by end date
                                            $sql = $sql . " AND registerDate <= '$enddate'";
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
                                            $sql= $sql . " and c.confirmationStatus_id = $status";
                                            $statement = $sql;
                                        }

                                        if($location!=""){
                                            $sql= $sql . " and c.pickupLocation_id = $location";
                                            $statement = $sql;
                                        }

                                        if($setup!=""){
                                            $sql= $sql . " and im.ready_id = $setup";
                                            $statement = $sql;
                                        }

                                            //$statement = $sql . " ORDER BY ord_ID DESC ";
                                            $rec_count = mysqli_num_rows($result);
                                                
                                            $sql= $sql . " ORDER BY 
                                                            CASE 
                                                                WHEN c.confirmationStatus_id !='' THEN c.confirmationStatus_id
                                                                ELSE c.pickUpDate
                                                            END ASC,
                                                            CASE 
                                                                WHEN c.confirmationStatus_id = 1 THEN c.pickUpDate
                                                                ELSE c.confirmationStatus_id
                                                            END ASC;";     

                                            $statement = $sql;
                                            //print $sql;
                                            $result = mysqli_query($conn, $sql);

                                        }else{
                                            //set semula tanpa filtering
                                            $sql = "SELECT
                                            im.*, DATE_FORMAT(im.registerDate, '%d-%m-%Y') AS registerDate,
                                            u.userName AS username, 
                                            i.name AS itemname, 
                                            i.image AS image, 
                                            c.confirmationStatus_id AS status, 
                                            c.currentLocation AS location,
                                            c.pickUpDuration As pduration,
                                            DATE_FORMAT(c.pickUpDate, '%d-%m-%Y %H:%i:%s') As pdate,
                                            DATE_FORMAT(dd.dueDate, '%d-%m-%Y %H:%i:%s') As ddDate,
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
                                        }

                                    ?>
                                    <thead>
                                        <tr>
                                            <th width="25px">Num.</th>
                                            <th width="75px" >Date</th>
                                            <th width="75px" >Due Date</th>
                                            <th>Resit ID</th>
                                            <th>PIC Item</th>
                                            <th>Image</th>
                                            <th>JomPick ID</th>
                                            <th>Owner Name</th>
                                            <th>Owner Location</th>
                                            <th>Pickup Location</th>
                                            <th>Pick-Up Date</th>
                                            <th width="75px">Pickup Duration</th>
                                            <th>Status</th>
                                            <th width="80px">Progress</th>
                                            <th>ACT</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Num.</th>
                                            <th width="75px" >Date</th>
                                            <th width="75px" >Due Date</th>
                                            <th>Resit ID</th>
                                            <th>PIC Item</th>
                                            <th>Image</th>
                                            <th>JomPick ID</th>
                                            <th>Owner Name</th>
                                            <th>Owner Location</th>
                                            <th>Pickup Location</th>
                                            <th>Pickup Date</th>
                                            <th width="75px">Pickup Duration</th>
                                            <th width="80px">Progress</th>
                                            <th>State</th>
                                            <th>Action</th>
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
                                            $pdate = $row['pdate'];
                                            $ready = $row['ready_id'];
                                            $pduration = $row['pduration'];
                                            $c_id = $row['c_id'];

                                            date_default_timezone_set('Asia/Kuala_Lumpur');
                                            $currentDate = new DateTime();  // Assuming $currentDate is a DateTime object
                                            $ddDate = new DateTime($row['ddDate']);  // Assuming $ddDate is a DateTime object
                                            
                                            if ($status == 1 || $status == 3) {
                                                // Check if the current date is greater than the due date
                                                if ($currentDate > $ddDate) {
                                                    // Calculate the difference in days
                                                    $diffInDays = $currentDate->diff($ddDate)->days;
                                            
                                                    // Check if the absolute difference is 7 days or more
                                                    if (abs($diffInDays) >= 7) {
                                                        $statusaa = '4'; // Set the status according to your logic;
                                            
                                                        // Update the confirmation status in the 'confirmation' table
                                                        $updateQueryConfirmation = "UPDATE confirmation 
                                                                                    SET 
                                                                                        confirmationStatus_id = $statusaa
                                                                                    WHERE 
                                                                                        confirmation_id = (SELECT confirmation_id FROM item_management WHERE resit_id = '$resit' LIMIT 1);";
                                            
                                                        $updateResultConfirmation = mysqli_query($conn, $updateQueryConfirmation);
                                                    }
                                                }
                                            }
                                            
                                            $ddDateString = $ddDate->format("d-m-Y");

                                            
                                            if($status == 1){
                                                if($ready == 1 ){
                                                    $readytext = 'Ready';
                                                    $colorr = 'success'; 
                                                    $iconr = 'check-square';
                                                    $visible = 'block';
                                                }
                                                if($ready == 2 ){
                                                    $readytext = 'Unready';
                                                    $colorr = 'danger'; 
                                                    $iconr = 'square';
                                                    $visible = 'block';
                                                    
                                                }
                                            } else if ($status == 2 || $status == 3 || $status == 4){
                                                $readytext = 'Pending';
                                                $colorr = 'danger'; 
                                                $iconr = 'square';
                                                $visible = 'none';
                                            }
                                                
                                            if ($status === '1') {
                                                $status = 'Pick now';
                                                $color = 'orange';    
                                            } else if ($status === '2') {
                                                $status = 'Picked';
                                                $color = 'green';  
                                            } else if ($status === '3') {
                                                $status = 'Pending';
                                                $color = 'red';  
                                            }
                                            else if ($status === '4') {
                                                $status = 'Disposed';
                                                $color = 'blue';  
                                            }
                                           
                                            $imageId = "smallImage_" . $x;

                                            ?>
                                            <tr>
                                                <td style="vertical-align: middle;" ><?php echo $x;?></td>
                                                <td style="vertical-align: middle;"><?php echo $registerdate; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $ddDateString; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $resit; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $username; ?></td>
                                                <td style="vertical-align: middle;"><img id="<?php echo $imageId;?>" src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode($image), ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100" onclick="showLargeImage('<?php echo $imageId; ?>')" /></td>
                                                <td style="vertical-align: middle;"><?php echo strtoupper($jompickid); ?></td>
                                                <td style="vertical-align: middle;"><?php echo $itemname; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $location; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $pickuplocation; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $pdate; ?></td>
                                                <td style="vertical-align: middle;"><?php echo $pduration; ?></td>
                                                <td style="vertical-align: middle;"><div style="color:<?php echo $color; ?>;"><?php echo $status; ?></div></td>
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <a href="function/ready-jompick.php?resitid=<?php echo $resit; ?>&ready=<?php echo $ready; ?>" class="btn btn-<?php echo $colorr; ?> btn-sm" style="margin-top: 3px; display: <?php echo $visible; ?>;">
                                                        <i class="fas fa-<?php echo $iconr; ?>" style="color: <?php echo $colorr; ?>;"></i> &nbsp;<?php echo $readytext; ?>
                                                    </a>
                                                </td>
                                                <td style="vertical-align: middle;"> 
                                                <!-- <a href="function/ready-jompick.php?location=<?php //echo $location_id; ?>" class="btn btn-info btn-sm" style="margin-top:3px;">More</a> -->
                                                    <a href="jompick-update.php?resitid=<?php echo $resit; ?>" class="btn btn-info btn-sm" style="margin-top:3px;"><i class="fas fa-edit"></i></a>
                                                    <!-- <a href="function/delete-jompick.php?resitid=<?php //echo $resit; ?>&c_id=<?php //echo $c_id; ?>" class="btn btn-danger btn-sm" style="margin-top:3px;" ><i class="fas fa-trash"></i></a> -->
                                                    
                                                </td>
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
    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->


</body>
</html>