<?php 

  include 'include/auth.php';

  if(isset($_SESSION['SESS_LEVEL'])){
    if($_SESSION['SESS_LEVEL'] == 1){
      header('location: pages/admin_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 2){
      header('location: pages/filer_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 3){
      header('location: pages/maker_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] >= 4 && $_SESSION['SESS_LEVEL'] <= 7){
      header('location: pages/approver_dashboard.php');
    } elseif($_SESSION['SESS_LEVEL'] == 8){
      header('location: pages/auditor_dashboard.php');
    }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Cache control meta tags -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>QC - Trouble Report System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logo.png">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="assets/css/style.css?v=1.1  " rel="stylesheet">

</head>

<body class="bg-image">
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card shadow" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-xl-6 col-lg-5 d-none d-md-block">
                <img src="assets/img/cover.svg" alt="login background" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-xl-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form id="loginDetails">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <img src="assets/img/logo.png" alt="Logo" style="width: 50px;" class="mr-3">
                      <span class="h4 font-weight-bold mb-0">Trouble Report System</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                    <div class="form-outline mb-4">
                      <input type="text" id="username" name="username" class="form-control form-control-lg" autofocus />
                      <label class="form-label" for="username">Username</label> <i class="small text-danger d-none">- Login or password is invalid.</i>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control form-control-lg" />
                      <label class="form-label" for="password">Password</label> <i class="small text-danger d-none">- Login or password is invalid.</i>
                    </div>

                    <!-- <div class="form-outline mb-4 d-flex justify-content-between align-items-center">
                      <label class="custom-checkbox mb-0">
                        <input name="dummy" type="checkbox">
                        <span class="checkmark"></span>
                        Remember for 30 days
                      </label>
                      <a href="forgot_password.php" class="small text-muted">Forgot password?</a>
                    </div> -->

                  </form>

                  <div class="pt-1 mb-4">
                    <button class="btn-block bounce" id="login" onclick="Login()">Login</button>
                  </div>

                  <div class="text-center">
                    <small>System Version</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- 
  Bootstrap core JavaScript
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

  <!-- Core plugin JavaScript-->
  <!-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> -->

  <!-- Custom scripts for all pages-->
  <!-- <script src="assets/js/sb-admin-2.min.js"></script> -->

  <!-- <script>
    $(document).on('keypress', function(event) {
      if (event.which === 13) { // 13 is the Enter key
        $('#login').click();
      }
    });
  </script> -->

  <!-- <script src="assets/js/script.js"></script> -->





  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>

  <script>
    $(document).on('keypress', function(event) {
      if (event.which === 13) { // 13 is the Enter key
        $('#login').click();
      }
    });
  </script>

  <script src="assets/js/script.js"></script>
</body>

</html>