<?php

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION["id"])) {
    header("Location: ../index.php");
    exit;
}

// Fetch user's name from the database
    $user_id = $_SESSION["id"];
    $sql = "SELECT userName, role_id FROM user WHERE LOWER(user_id) = LOWER('$user_id')";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $user_role = $row ['role_id'];
    $user_name = $row["userName"];
?>

<?php  if (($user_role == '1')){ ?>
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                        <img src="assets/JomPick_logo5.jpg" alt="Logo" style="width:30px;height:30px;"/>
                </div>
                <div class="sidebar-brand-text mx-3">JomPick</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == "index.php") {echo 'active';} ?> ">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Employee
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "location-register.php" || $page == "location-list.php" || $page == "location-update.php") {echo 'active';} ?> ">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#location"
                    aria-expanded="true" aria-controls="location">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Our Locations</span>
                </a>
                <div id="location" class="collapse <?php if($page == "location-register.php" || $page == "location-list.php" || $page == "location-update.php") {echo 'show';} ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Location's Sections:</h6>
                        <a class="collapse-item" href="location-register.php">Add New</a>
                        <a class="collapse-item" href="location-list.php">List</a>
                    </div>
                </div>
            </li>
            
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "manager-register.php" || $page == "manager-list.php" || $page == "manager-update.php") {echo 'active';} ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#manager"
                    aria-expanded="true" aria-controls="manager">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Manager</span>
                </a>
                <div id="manager" class="collapse <?php if($page == "manager-register.php" || $page == "manager-list.php" || $page == "manager-update.php") {echo 'show';} ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manager's Sections:</h6>
                        <a class="collapse-item" href="manager-register.php">Add New</a>
                        <a class="collapse-item" href="manager-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "staff-register.php" || $page == "staff-list.php" || $page == "staff-update.php") {echo 'active';} ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#staff"
                    aria-expanded="true" aria-controls="staff">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Staff</span>
                </a>
                <div id="staff" class="collapse <?php if($page == "staff-register.php" || $page == "staff-list.php" || $page == "staff-update.php") {echo 'show';} ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Staff's Sections:</h6>
                        <a class="collapse-item" href="staff-register.php">Add New</a>
                        <a class="collapse-item" href="staff-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Management
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "jompick-register.php" || $page == "jompick-list.php" || $page == "jompick-update.php") {echo 'active';} ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#jompick"
                    aria-expanded="true" aria-controls="jompick">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Item</span>
                </a>
                <div id="jompick" class="collapse <?php if($page == "jompick-register.php" || $page == "jompick-list.php" || $page == "jompick-update.php" ) {echo 'show';} ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Item's Sections:</h6>
                        <a class="collapse-item" href="jompick-register.php">Add New</a>
                        <a class="collapse-item" href="jompick-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item <?php if($page == "jompick-penalty-list.php" || $page == "jompick-penalty-update.php") {echo 'active';} ?>">
                <a class="nav-link" href="jompick-penalty-list.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Penalty</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item <?php if($page == "customer-list.php" || $page == "customer-update.php") {echo 'active';} ?>">
                <a class="nav-link" href="customer-list.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Customer List</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
<?php  } ?>

<?php  if (($user_role == '2')){ ?>
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                        <img src="assets/JomPick_logo5.jpg" alt="Logo" style="width:30px;height:30px;"/>
                </div>
                <div class="sidebar-brand-text mx-3">JomPick</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == "index.php") {echo 'active';} ?> ">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Employee
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "staff-register.php" || $page == "staff-list.php" || $page == "staff-update.php") {echo 'active';} ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#staff"
                    aria-expanded="true" aria-controls="staff">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Staff</span>
                </a>
                <div id="staff" class="collapse <?php if($page == "staff-register.php" || $page == "staff-list.php" || $page == "staff-update.php") {echo 'show';} ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Staff's Sections:</h6>
                        <a class="collapse-item" href="staff-register.php">Add New</a>
                        <a class="collapse-item" href="staff-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Management
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($page == "jompick-register.php" || $page == "jompick-list.php" || $page == "jompick-update.php" ) {echo 'active';} ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#jompick"
                    aria-expanded="true" aria-controls="jompick">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Item</span>
                </a>
                <div id="jompick" class="collapse <?php if($page == "jompick-register.php" || $page == "jompick-list.php" || $page == "jompick-update.php") {echo 'show';} ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Item's Sections:</h6>
                        <a class="collapse-item" href="jompick-register.php">Add New</a>
                        <a class="collapse-item" href="jompick-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item <?php if($page == "jompick-penalty-list.php" || $page == "jompick-penalty-update.php") {echo 'active';} ?>">
                <a class="nav-link" href="jompick-penalty-list.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Penalty</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item <?php if($page == "customer-list.php" || $page == "customer-update.php") {echo 'active';} ?>">
                <a class="nav-link" href="customer-list.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Customer List</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

<?php  } ?>

<?php  if (($user_role == '3')){ ?>
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                        <img src="assets/JomPick_logo5.jpg" alt="Logo" style="width:30px;height:30px;"/>
                </div>
                <div class="sidebar-brand-text mx-3">JomPick</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Management
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed <?php if($page == "jompick-register.php" || $page == "jompick-list.php" || $page == "jompick-update.php") {echo 'active';}?>" href="#" data-toggle="collapse" data-target="#jompick"
                    aria-expanded="true" aria-controls="jompick">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Item</span>
                </a>
                <div id="jompick" class="collapse <?php if($page == "jompick-register.php" || $page == "jompick-list.php" ) {echo 'show';} ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Item's Sections:</h6>
                        <a class="collapse-item" href="jompick-register.php">Register</a>
                        <a class="collapse-item" href="jompick-list.php">List</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item <?php if($page == "jompick-penalty-list.php" || $page == "jompick-penalty-update.php" ) {echo 'active';}?>">
                <a class="nav-link" href="jompick-penalty-list.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Penalty</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item <?php if($page == "customer-list.php" || $page == "customer-update.php") {echo 'active';}?>">
                <a class="nav-link" href="customer-list.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Customer List</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

<?php  } ?>