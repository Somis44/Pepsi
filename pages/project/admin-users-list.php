<?php
session_start();

if($_SESSION['role'] != 3){
    header('Location: login.php');
}

if (!isset($_SESSION['loggedin']) || session_status() != 2 || session_id() != $_SESSION['session_id']) {
    $_SESSION["error"] = "Please login";
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MonoBank | Users List</title>

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
                    <img src="../dist/img/user.png" class="img-circle elevation-2" alt="User Image"">
                </div>
                <div class="info">
                    <a href="profile.php" class="d-block"><?php echo $_SESSION['firstName']," ",$_SESSION['lastName'];?></a>
                    <a class="text-gray"> Admin </a>
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
                        <a href="admin-users-list.php" class="nav-link">
                            <i class="nav-icon fas fa-users-cog text-danger"></i>
                            <p class="text-danger">Users List</p>
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
                        <h1>Users List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Users List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Users</h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 400px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Account</th>
                            <th>Role</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Birthday</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        require_once "../../scripts/connect.php";
                        $sql = "select u.id as id, c.city as city, r.role as role, u.account as account, u.email as email, CONCAT(u.firstName,' ',u.lastName) as fullname, u.birthday as birthday, u.created_at as created from user u join cities c on u.city_id = c.id join roles r on u.role_id = role;;";
                        $result = $conn->query($sql);

                        if($result->num_rows==0){
                            echo "<tr><td colspan='6'>Database don't have any users yet (wtf? it's imposible)</td></tr>";
                        }else {
                            while ($user = $result->fetch_assoc()) {
                                echo <<< TABLEUSERS
                            <tr>
                                <td>$user[id]</td>
                                <td>$user[account]</td>
                                <td>$user[role]</td>
                                <td>$user[fullname]</td>
                                <td>$user[email]</td>
                                <td>$user[city]</td>
                                <td>$user[birthday]</td>
                                <td>$user[created]</td>
                                <td><a href="./admin-users-list.php?userUpdateId=$user[id]"><button class="btn btn-primary">Update</button></a></td>
                                <td><a href="./admin-users-list.php?userDeleteId=$user[id]"><button class="btn btn-danger">Delete</button></a></td>                     
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

            <?php
            if(isset($_SESSION["success"])){
                echo <<< SUCCESS
                <div class="callout callout-success">
                <h5>Success!</h5>
                <p>$_SESSION[success]</p>
                </div>
    SUCCESS;
                unset($_SESSION["success"]);
            }

            if (isset($_SESSION["error"])){
                echo <<< ERROR
      <div class="callout callout-danger">
                  <h5>Error!</h5>
                  <p>$_SESSION[error]</p>
                </div>
ERROR;
                unset($_SESSION["error"]);
            }
            ?>

            <?php
            if (isset($_GET["userUpdateId"])){
                $_SESSION["userUpdateId"] = $_GET["userUpdateId"];
                $sql = "SELECT role_id, firstName, lastName, email, city_id, birthday FROM user u WHERE u.id=$_GET[userUpdateId]";
                $result = $conn->query($sql);
                $upd_user = $result->fetch_assoc();
                echo <<< UPDATEUSER
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">User Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="../../scripts/update-user.php" method="post">
                <div class="card-body">
                
                <div class="form-group">
                  <label>Role</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="role_id" style="width: 100%;">
UPDATEUSER;
                require_once "../../scripts/connect.php";
                $sql ="SELECT * FROM roles";
                $result = $conn->query($sql);
                while($role = $result->fetch_assoc()) {
                    if($role["id"] == $upd_user["role_id"]){
                        echo "<option value='$role[id]' selected>$role[role]</option>>";
                    }else{
                        echo "<option value='$role[id]'>$role[role]</option>";
                    }
                }
                echo <<< UPDATEUSER
                    </select>
                </div>
                
                  <div class="form-group">
                    <label for="exampleInputPassword1">First Name</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" value="$upd_user[firstName]" name="firstName">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Last Name</label>
                    <input type="text" class="form-control" id="exampleInputPassword1" value="$upd_user[lastName]" name="lastName">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" id="exampleInputPassword1" value="$upd_user[email]" name="email">
                  </div>
                  <div class="form-group">
                  <label>City</label>
                  <select class="form-control select2bs4 select2-hidden-accessible" name="city_id" style="width: 100%;">
UPDATEUSER;
                            require_once "../../scripts/connect.php";
                            $sql ="SELECT * FROM cities";
                            $result = $conn->query($sql);
                            while($city = $result->fetch_assoc()) {
                                if($city["id"] == $upd_user["city_id"]){
                                    echo "<option value='$city[id]' selected>$city[city]</option>>";
                                }else{
                                    echo "<option value='$city[id]'>$city[city]</option>";
                                }
                            }
                            echo <<< UPDATEUSER
                    </select>
                </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Birthday</label>
                    <input type="date" class="form-control" id="exampleInputPassword1" value="$upd_user[birthday]" name="birthday">
                  </div>
                  <div class="form-group mb-0">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check">
                        <label class="form-check-label" for="exampleCheck1">Confirm the user changes</label>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="./admin-users-list.php"><button type="button" class="btn btn-gray">Back</button></a>
                </div>
              </form>
            </div>
        UPDATEUSER;
            }
            ?>

            <?php
            if (isset($_GET["userDeleteId"])){
                $_SESSION["userDeleteId"] = $_GET["userDeleteId"];
                $sql = "SELECT firstName, lastName FROM user u WHERE u.id=$_GET[userDeleteId]";
                $result = $conn->query($sql);
                $del_user = $result->fetch_assoc();
                echo <<< DELETEUSER
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Delete User</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-10">
                  <p>Are you sure you want to delete $del_user[firstName] $del_user[lastName] ?</p>
                  </div>

                  <div class="col-2">
                    <a href="../../scripts/delete-user.php"><button type="submit" class="btn btn-primary">Yes, I'm sure</button></a>
                    <a href="./admin-users-list.php"><button type="submit" class="btn btn-gray">No, I don't</button></a>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
DELETEUSER;

            }
            ?>

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
