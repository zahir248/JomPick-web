<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
$page = 'staff-list.php'; 
$screen_name = 'Staff List';

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
                    <h1 class="h3 mb-4 text-gray-800">Staff List</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search</h6>
                        </div>
                        <form method="GET" id="myForm" action="#!" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="jpid">JomPick_ID:</label><br/>
                                                <input type="text" class="form-control" id="jpid" name="jpid" value="<?php echo isset($_GET['jpid']) ? $_GET['jpid'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="username">Username:</label><br/>
                                                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="phnum">Phone Number:</label><br/>
                                                <input type="text" class="form-control" id="phnum" name="phnum" value="<?php echo isset($_GET['phnum']) ? $_GET['phnum'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="icnum">Ic Number:</label><br/>
                                                <input type="text" class="form-control" id="icnum" name="icnum" value="<?php echo isset($_GET['icnum']) ? $_GET['icnum'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="fullname">Full Name:</label><br/>
                                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo isset($_GET['fullname']) ? $_GET['fullname'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="mailaddress">E-Mail:</label><br/>
                                                <input type="text" class="form-control" id="mailaddress" name="mailaddress"  value="<?php echo isset($_GET['mailaddress']) ? $_GET['mailaddress'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-3" >
                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <div><button type="submit" onclick="resetForm()" class="btn btn-primary btn-sm" name="carian" value="carian" id="carian">Reset</button></div>
                                    </div>
                                    <script>
                                        function resetForm() {
                                            document.forms["myForm"]["jpid"].value = '';
                                            document.forms["myForm"]["username"].value = '';
                                            document.forms["myForm"]["phnum"].value = '';
                                            document.forms["myForm"]["icnum"].value = '';
                                            document.forms["myForm"]["fullname"].value = '';
                                            document.forms["myForm"]["mailaddress"].value = '';
                                            document.forms["myForm"]["jpid"].value = '';
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
                            <h6 class="m-0 font-weight-bold text-primary">Managers List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <?Php
                                    $sql = "SELECT u.*, r.rolename, pl.name as address FROM user u 
                                            JOIN role r ON u.role_id = r.role_id 
                                            JOIN pickup_location pl ON u.jp_location_id = pl.pickupLocation_id
                                            WHERE u.role_id = 3 and u.Availability_id = 1"; 
                                        //filtering listing
                                        if (isset($_GET['carian'])) {
                                            $jpid=$_GET['jpid'];
                                            $username=$_GET['username'];
                                            $phnum=$_GET['phnum'];
                                            $icnum=$_GET['icnum'];
                                            $fullname=$_GET['fullname'];
                                            $mailaddress=$_GET['mailaddress'];

                                        if($jpid!=""){
                                            $sql= $sql . " and JomPick_ID LIKE '%$jpid%'";
                                            $statement = $sql;
                                        } 
                                        if($username!=""){
                                            $sql= $sql . " and userName LIKE '%$username%'";
                                            $statement = $sql;
                                        }
                                        if($phnum!=""){
                                            $sql= $sql . " and phoneNumber LIKE '%$phnum%'";
                                            $statement = $sql;
                                        }
                                        if($icnum!=""){
                                            $sql= $sql . " and icNumber LIKE '%$icnum%'";
                                            $statement = $sql;
                                        }

                                        if($fullname!=""){
                                            $sql= $sql . " and fullName LIKE '%$fullname%'";
                                            $statement = $sql;
                                        }
                                        
                                        if($mailaddress!=""){
                                            $sql= $sql . " and emailAddress LIKE '%$mailaddress%'";
                                            $statement = $sql;
                                        }

                                            //$statement = $sql . " ORDER BY ord_ID DESC ";
                                            $rec_count = mysqli_num_rows($result);
                                                
                                            $sql= $sql . " ORDER BY JomPick_ID asc";          
                                            $statement = $sql;
                                            //print $sql;
                                            $result = mysqli_query($conn, $sql);

                                        }else{
                                            //set semula tanpa filtering
                                            $sql = "SELECT u.*, r.rolename, pl.name as address FROM user u 
                                            JOIN role r ON u.role_id = r.role_id 
                                            JOIN pickup_location pl ON u.jp_location_id = pl.pickupLocation_id
                                            WHERE u.role_id = 3 and u.Availability_id = 1 
                                            ORDER BY JomPick_ID asc"; 
                                            $result = mysqli_query($conn, $sql);
                                            //print $sql;
                                        }

                                
                                    ?>
                                        <thead>
                                            <tr>
                                                <th>Num.</th>
                                                <th>JomPick ID</th>
                                                <th>Username</th>
                                                <th>Phone Number</th>
                                                <th>Ic Number</th>
                                                <th>Full Name</th>
                                                <th>E-Mail</th>
                                                <th>Location</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Num.</th>
                                                <th>JomPick ID</th>
                                                <th>Username</th>
                                                <th>Phone Number</th>
                                                <th>Ic Number</th>
                                                <th>Full Name</th>
                                                <th>E-Mail</th>
                                                <th>Location</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php $x=1;
                                                while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){

                                                $jpid = $row['JomPick_ID'];
                                                $userName = $row['userName'];
                                                $phoneNumber = $row['phoneNumber'];
                                                $icNumber = $row['icNumber'];
                                                $fullName = $row['fullName'];
                                                $emailAddress = $row['emailAddress'];
                                                $userlocationid= $row['jp_location_id'];
                                                $address = $row['address'];

                                               

                                                ?>
                                                <tr>
                                                    <td><?php echo $x;?></td>
                                                    <td><?php echo $jpid; ?></td>
                                                    <td><?php echo $userName; ?></td>
                                                    <td><?php echo $phoneNumber; ?></td>
                                                    <td><?php echo $icNumber; ?></td>
                                                    <td><?php echo $fullName; ?></td>
                                                    <td><?php echo $emailAddress; ?></td>
                                                    <td><?php echo $address; ?></td>
                                                    <td>
                                                        <a href="staff-update.php?jpid=<?php echo $jpid; ?>" class="btn btn-info btn-sm"style="margin-top:3px;"><i class="fas fa-edit"></i></a>
                                                        <button class="btn btn-danger btn-sm" style="margin-top:3px;" data-toggle="modal" data-target="#deleteModal<?php echo $jpid; ?>"><i class="fas fa-trash-alt"></i></button>
                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="deleteModal<?php echo $jpid; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="deleteModalLabel">Delete Staff</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- Add your delete confirmation message here -->
                                                                        <p>Are you sure you want to delete this staff: <?php echo $userName; ?>?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <a href="function/delete-staff.php?jpid=<?php echo $jpid; ?>" class="btn btn-danger">Delete</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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

    <!-- Foot -->
    <?php include 'includecode/foot.php' ?>
    <!-- Foot -->


</body>
</html>