<?php 
  include 'auth.php'; 

  if(isset($_SESSION['SESS_LEVEL'])){
    if($_SESSION['SESS_LEVEL'] == 1){
      header('location: ../pages/admin_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 2){
      // header('location: ../pages/filer_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 3){
      header('location: ../pages/editor_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] >= 4 && $_SESSION['SESS_LEVEL'] <= 7){
      header('location: ../pages/approver_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 8){
      header('location: ../pages/auditor_dashboard.php');
    }
  } else{
    header('location: ../index.php');
  }

  function getUser($user_id){
    global $conn;
    $result = mysqli_query($conn, "SELECT username FROM tbl_account WHERE id = '$user_id'");
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      return $row['username'];
    } else {
      return null;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>QC - TRS | Requestor</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/logo.png">

  <?php include 'link.php'; ?>
</head>

<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="filer_dashboard.php">
        <div class="sidebar-brand-icon">
        <img src="../assets/img/logo.png" alt="" class="img-fluid" style="width: 45px;">
        </div>
        <div class="sidebar-brand-text mx-2">Trouble Report System</div>
      </a>

      <hr class="sidebar-divider my-0">

      <li class="nav-item">
        <a class="nav-link" href="../pages/filer_dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../pages/requestor_request.php">
          <i class="fas fa-fw fa fa-file" aria-hidden="true"></i>
          <span>Request Trouble Report</span></a>
      </li>
      
      <!-- <li class="nav-item">
        <a class="nav-link" href="../pages/dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li> -->

      <!-- <hr class="sidebar-divider"> -->

      <!-- <div class="sidebar-heading">
        Components
      </div> -->

      <!-- <li class="nav-item">
        <a class="nav-link" href="link.html">
          <i class="fas fa-fw fa-users"></i>
          <span>Link</span></a>
      </li> -->

      <!-- <hr class="sidebar-divider d-none d-md-block"> -->

      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <ul class="navbar-nav ml-auto">
            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small pt-2"><?php echo $_SESSION['SESS_USERID'] ? getUser($_SESSION['SESS_USERID']) : '' ?></span>
                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg" alt="Picture">
              </a>

              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <!-- <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div> -->
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
      