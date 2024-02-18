<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
$page = 'location-list.php'; 
$screen_name = 'Location List';

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
                    <h1 class="h3 mb-4 text-gray-800">Location's List</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Search</h6>
                        </div>
                        <form method="GET" id="myForm" action="location-list.php" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="locationid">Location ID:</label><br/>
                                                <input type="text" class="form-control" id="locationid" name="locationid" value="<?php echo isset($_GET['locationid']) ? $_GET['locationid'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="name">Location Name:</label><br/>
                                                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                                <label for="address">Location Address:</label><br/>
                                                <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($_GET['address']) ? $_GET['address'] : ''; ?>">
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
                                            document.forms["myForm"]["locationid"].value = '';
                                            document.forms["myForm"]["name"].value = '';
                                            document.forms["myForm"]["address"].value = '';
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
                            <h6 class="m-0 font-weight-bold text-primary">Locations List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <?Php
                                        $sql = "SELECT l.* FROM pickup_location l WHERE availability_id = 1 "; 
                                        //filtering listing
                                        if (isset($_GET['carian'])) {
                                            $locationid=$_GET['locationid'];
                                            $name=$_GET['name'];
                                            $address=$_GET['address'];


                                        if($locationid!=""){
                                            $sql= $sql . " and pickupLocation_id = '$locationid'";
                                            $statement = $sql;
                                        } 
                                        if($name!=""){
                                            $sql= $sql . " and name LIKE '%$name%'";
                                            $statement = $sql;
                                        }

                                        if($address!=""){
                                            $sql= $sql . " and address LIKE '%$address%'";
                                            $statement = $sql;
                                        }
                                

                                            //$statement = $sql . " ORDER BY ord_ID DESC ";
                                            $rec_count = mysqli_num_rows($result);
                                                
                                            $sql= $sql . " ORDER BY pickupLocation_id asc";          
                                            $statement = $sql;
                                            //print $sql;
                                            $result = mysqli_query($conn, $sql);

                                        }else{
                                            //set semula tanpa filtering
                                            $sql = "SELECT l.* FROM pickup_location l WHERE availability_id = 1 ORDER BY pickupLocation_id asc"; 
                                            $result = mysqli_query($conn, $sql);
                                            //print $sql;
                                        }

                                
                                    ?>
                                    <thead>
                                        <script>
                                        function checkAll(bx) {
                                            var cbs = document.getElementsByTagName('input');
                                            for(var i=0; i < cbs.length; i++) {
                                                if(cbs[i].type == 'checkbox') {
                                                cbs[i].checked = bx.checked;
                                                }
                                            }
                                        }</script>
                                        <tr>
                                            <!-- <th style="width:25px;">&nbsp;&nbsp;<input type="checkbox" onclick="checkAll(this)"></th> -->
                                            <th style="width:50px;">Num.</th>
                                            <th>Location Name</th>
                                            <th>Location Address</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <!-- <th style="width:25px;">&nbsp;&nbsp;<input type="checkbox" onclick="checkAll(this)"></th> -->
                                            <th style="width:50px;">Num.</th>
                                            <th>Location Name</th>
                                            <th>Location Address</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $x=1;
                                            while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){

                                            $address = $row['address'];
                                            $image = $row['image'];
                                            $locationname = $row['name'];
                                            $location_id = $row['pickupLocation_id'];
                                            
                                            ?>
                                            <tr>
                                                <!-- <td>&nbsp;&nbsp;<input type="checkbox" name="name1" /></td> -->
                                                <td style="text-align: center;"><?php echo $x;?></td>
                                                <td><?php echo $locationname; ?></td>
                                                <td><?php echo $address; ?></td>
                                                <td><img src="data:image/jpeg;base64,<?php echo htmlspecialchars(base64_encode($image), ENT_QUOTES, 'UTF-8'); ?>" width="100" height="100" /></td>
                                                <td> 
                                                    <a href="location-update.php?location=<?php echo $location_id; ?>" class="btn btn-info btn-sm" style="margin-top:3px;"><i class="fas fa-edit"></i></a>
                                                    <button class="btn btn-danger btn-sm" style="margin-top:3px;" data-toggle="modal" data-target="#deleteModal<?php echo $location_id; ?>"><i class="fas fa-trash"></i></button>
                                                    <div class="modal fade" id="deleteModal<?php echo $location_id; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel">Delete Location</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Add your delete confirmation message here -->
                                                                    <p>Are you sure you want to delete <?php echo $locationname; ?> ?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a href="function/delete-location.php?location=<?php echo $location_id; ?>" class="btn btn-danger">Delete</a>
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
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
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