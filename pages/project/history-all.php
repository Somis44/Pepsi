<?php
session_start();

if (!isset($_SESSION['loggedin']) || session_status() != 2 || session_id() != $_SESSION['session_id']) {
    $_SESSION["error"] = "Please login";
    header('Location: login.php');
    exit;
}

if($_SESSION['role'] == 1){
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MonoBank | Transactions history</title>

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
                    <?php if($_SESSION['role'] == 2) {echo "<a class=text-gray> Moderator </a>";}elseif ($_SESSION['role'] == 3) {echo "<a class=text-gray> Admin </a>";}else{echo "<a class=text-gray> User </a>";}?>
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
                    <?php
                    if($_SESSION['role'] == 3){
                        echo <<< ADMINPAGES
            <li class="nav-item">
                <a href="admin-users-list.php" class="nav-link">
                    <i class="nav-icon fas fa-users-cog text-danger"></i>
                    <p class="text-danger">Users List</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="balance-all.php" class="nav-link">
                    <i class="nav-icon fas fa-money-bill-wave text-danger"></i>
                    <p class="text-danger">Users Balance</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="history-all.php" class="nav-link">
                    <i class="nav-icon fas fa-history text-danger"></i>
                    <p class="text-danger">Transactions History</p>
                </a>
            </li>
ADMINPAGES;
                    }
                    if($_SESSION['role'] == 2){
                        echo <<< MODERPAGES
            <li class="nav-item">
                <a href="moder-users-list.php" class="nav-link">
                    <i class="nav-icon fas fa-users-cog text-warning"></i>
                    <p class="text-warning">Users List</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="history-all.php" class="nav-link">
                    <i class="nav-icon fas fa-history text-warning"></i>
                    <p class="text-warning">Transactions History</p>
                </a>
            </li>
MODERPAGES;
                    }
                    ?>
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
                        <h1>Transactions history</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Transactions history</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transactions history</h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 725px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sender</th>
                            <th>Sender_ID</th>
                            <th>Recipient</th>
                            <th>Recipient_ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        require_once "../../scripts/connect.php";
                        $sql = "select h.id as hid, CONCAT(u.firstName,' ',u.lastName) as sender, h.sender_id as hsender_id,CONCAT(u2.firstName,' ',u2.lastName) as recipient ,h.recipient_id as hrecipient_id, h.amount as amount, h.date as date from history h join user u on sender_id = u.account join user u2 on recipient_id = u2.account order by date desc;";
                        $result = $conn->query($sql);

                        if($result->num_rows==0){
                            echo "<tr><td colspan='6'>You don't have any transaction history yet</td></tr>";
                        }else {
                            while ($user = $result->fetch_assoc()) {
                                echo <<< TABLEUSERS
                            <tr>
                                <td>$user[hid]</td>
                                <td>$user[sender]</td>
                                <td>$user[hsender_id]</td>
                                <td>$user[recipient]</td>
                                <td>$user[hrecipient_id]</td>
                                <td>$user[amount] $</td>
                                <td>$user[date]</td>
                            </tr>
                        TABLEUSERS;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

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
