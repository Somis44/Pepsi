<?php
session_start();

if (!isset($_SESSION['loggedin']) || session_status() != 2 || session_id() != $_SESSION['session_id']) {
    $_SESSION["error"] = "Please login";
    header('Location: login.php');
    exit;
}

require_once "../../scripts/connect.php";


$stmt = $conn->prepare('select r.role,c.city,s.state,c2.country,u.account,u.birthday,u.created_at from user u join roles r on u.role_id = r.id join cities c on u.city_id = c.id join states s on c.state_id = s.id join countries c2 on s.country_id = c2.id where u.id = ?;');
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
$stmt->bind_param('s', $_SESSION['id']);
$stmt->execute();
// Store the result so we can check if the account exists in the database.
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($role,$city, $state, $country,$account, $birthday, $created_at);
    $stmt->fetch();

    $formattedBirth = date("d F Y", strtotime($birthday));
    $formattedCreated = date("H:i d F Y", strtotime($created_at));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MonoBank | Profile Page</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="user-home-page.php" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
            </li>

            <!-- Messages Dropdown Menu -->

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="user-home-page.php" class="brand-link">
            <img src="../dist/img/M-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight">MONObank</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="profile.php" class="d-block"><?php echo $_SESSION['firstName']," ",$_SESSION['lastName'];?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="user-home-page.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Home page</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="operations.php" class="nav-link">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>Operations</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="history.php" class="nav-link">
                            <i class="nav-icon fas fa-table"></i>
                            <p>History</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../scripts/logout.php" class="nav-link">
                            <i class="nav-icon far fa-circle text-danger"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>


            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
<div class = "container-fluid">
    <div class = "row">
            <div class="col-md-5">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="../dist/img/user-dark.png" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center"> <?php echo $_SESSION['firstName']," ",$_SESSION['lastName'];?></h3>

                        <p class="text-muted text-center"><?php echo $role; ?></p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b><i class="fas fa-user mr-2"></i> Account ID <a class="float-right"><?php echo $account; ?></a></b>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-map-marker-alt mr-2"></i> Address</b> <a class="float-right"><?php echo $city . " / " . $state . " / " . $country ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-mail-bulk mr-2"></i>Email</b> <a class="float-right"><?php echo $_SESSION['email'];?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-birthday-cake mr-2"></i>Birthday</b> <a class="float-right"><?php echo $formattedBirth; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fas fa-tools mr-2"></i>Account created at</b> <a class="float-right"><?php echo $formattedCreated; ?></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-7">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Account Balance</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <strong><i class="fas fa-money-bill mr-1"></i> Balance</strong>
                        <p class="text-muted">
                        <?php
                        require_once "../../scripts/connect.php";

                        $sql = "SELECT balance from balance where account_id = $_SESSION[id]";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = mysqli_fetch_assoc($result)){
                                echo $row["balance"] . " $";
                            }
                        }else{
                            echo "0";
                        }
                        ?></p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Last activity</h3>
                    </div>

                    <?php
                    require_once "../../scripts/connect.php";
                    $sql = "select CONCAT(u.firstName,' ',u.lastName) as sender,CONCAT(u2.firstName,' ',u2.lastName) as recipient , h.amount as amount, h.date as date from history h join user u on sender_id = u.account join user u2 on recipient_id = u2.account where u.id = $_SESSION[id] or u2.id = $_SESSION[id] limit 1;";
                    $result = $conn->query($sql);

                    if($result->num_rows==0){
                        $lastR = "You don't have any transaction history yet";
                        $lastS = "You don't have any transaction history yet";
                        $DateO= "You don't have any transaction history yet";
                        $AmountO = "You don't have any transaction history yet";
                    }else {
                        while ($user = $result->fetch_assoc()) {
                            $lastR = $user["recipient"];
                            $lastS = $user["sender"];
                            $DateO = $user["date"];
                            $AmountO = $user["amount"];
                        }
                    }

                    ?>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-user-clock mr-1"></i> Last receiver</strong>

                        <p class="text-muted"><?php  echo $lastR ?></p>

                        <hr>

                        <strong><i class="fas fa-user-clock mr-1"></i> Last sender</strong>

                        <p class="text-muted"><?php echo $lastS ?></p>

                        <hr>

                        <strong><i class="fas fa-clock mr-1"></i> Date of the last operation</strong>

                        <p class="text-muted"><?php echo $DateO ?></p>

                        <hr>

                        <strong><i class="fas fa-money-bill mr-1"></i> Amount of last operation</strong>

                        <p class="text-muted"><?php echo $AmountO ?></p>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
</div>
</div>
        </section>

        <!-- Main content -->
        <section class="content">

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.2.0
        </div>
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
