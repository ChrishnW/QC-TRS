<?php 
  include '../include/header_admin.php'; 

  // Get role name ....................................................................................
  function get_roleName($role) {
    switch ($role) {
      case 2:
        return "Requestor";
      case 3:
        return "Editor";
      case 4:
        return "Department Head";
      case 5:
        return "QC Supervisor";
      case 6:
        return "Factory Officer";
      case 7:
        return "Chief Operating Officer";
        case 8:
          return "Auditor";
      default:
        return "Unknown Role";
    }
  }

  // Get status name ..................................................................................
  function get_statusName($status) {
    switch ($status) {
      case 1:
        return "Active";
      case 0:
        return "Inactive";
      default:
        return "Unknown Status";
    }
  }     

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add account ....................................................................................
    if (isset($_POST['add_account'])) {
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
      $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $role = $_POST['role'];
      $pass = 12345;
      $password = password_hash($pass, PASSWORD_DEFAULT);
      $status = 1;

      // Validate email format
      // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      //   $_SESSION["message"] = "Invalid email format.";
      //   header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //   ob_end_flush();
      //   exit;
      // }

      // Check for duplicate username
      // $check_username = mysqli_query($conn, "SELECT * FROM tbl_account WHERE username='$username'");
      // if (mysqli_num_rows($check_username) > 0) {
      //     $_SESSION["message"] = "Username already exists.";
      //     header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //     ob_end_flush();
      //     exit;
      // }

      // Check for duplicate email
      // $check_email = mysqli_query($conn, "SELECT * FROM tbl_account WHERE email='$email'");
      // if (mysqli_num_rows($check_email) > 0) {
      //     $_SESSION["message"] = "Email already exists.";
      //     header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //     ob_end_flush();
      //     exit;
      // }

      $result = mysqli_query($conn, "INSERT INTO tbl_account (username, firstname, lastname, email, password, access, status) VALUES ('$username', '$firstname', '$lastname', '$email', '$password', '$role', '$status')");

      if($result){
          $_SESSION["message"] = "Account added successfully.";
      }
      else{
          $_SESSION["message"] = "Failed to add account.";
      }

      header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      ob_end_flush();
      exit;
    }

    // Edit account ..................................................................................
    if (isset($_POST['edit_account'])) {
      $_SESSION["edit_account_id"] = $_POST['id_account'];

      header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      ob_end_flush();
      exit;
    }

    // Edit account submit ...........................................................................
    if (isset($_POST['edit_account_submit'])) {
      $id = $_POST['id'];
      $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
      $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
      $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $role = $_POST['role'];
      $status = $_POST['status'];

      $result = mysqli_query($conn, "UPDATE tbl_account SET username='$username', firstname='$firstname', lastname='$lastname', email='$email', access='$role', status='$status' WHERE id='$id'");

      // Validate email format
      // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      //   $_SESSION["message"] = "Invalid email format.";
      //   header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //   ob_end_flush();
      //   exit;
      // }

      // Check for duplicate username
      // $check_username = mysqli_query($conn, "SELECT * FROM tbl_account WHERE username='$username'");
      // if (mysqli_num_rows($check_username) > 0) {
      //     $_SESSION["message"] = "Username already exists.";
      //     header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //     ob_end_flush();
      //     exit;
      // }

      // Check for duplicate email
      // $check_email = mysqli_query($conn, "SELECT * FROM tbl_account WHERE email='$email'");
      // if (mysqli_num_rows($check_email) > 0) {
      //     $_SESSION["message"] = "Email already exists.";
      //     header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      //     ob_end_flush();
      //     exit;
      // }

      if($result){
          $_SESSION["message"] = "Account updated successfully.";
      }
      else{
          $_SESSION["message"] = "Failed to update account.";
      }

      header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      ob_end_flush();
      exit;
    }

    // Delete account ..................................................................................
    if (isset($_POST['delete_account'])) {
      $_SESSION["delete_account_id"] = $_POST['id_account'];

      header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      ob_end_flush();
      exit;
    }

    // Delete account submit ...........................................................................
    if (isset($_POST['delte_account_submit'])) {
      $id = $_POST['id'];

      $result = mysqli_query($conn, "DELETE FROM tbl_account WHERE id='$id' ");

      if($result){
          $_SESSION["message"] = "Account deleted successfully.";
      }
      else{
          $_SESSION["message"] = "Failed to delete account.";
      }

      header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
      ob_end_flush();
      exit;
    }
  }
?>

<!-- Account Dashboard-->
<div class="container-fluid">
  <div id="account_dashboard" class="account_dashboard" style="display: block;">
    <div class="card shadow mb-4">
      <div class="card-header py-3.5 pt-4">
        <h2 class="float-left">Account List</h2>
        <button id="btn_add_account" type="button" class="btn btn-primary float-right">
          <i class="fa fa-plus pr-1"></i> Add Account
        </button>
        <div class="clearfix"></div>
      </div>
        
      <div class="card-body">
        <div class="table-responsive">
          <table class=" table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class="bg-primary text-white">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th style="width: 170px;">Actions</th>
              </tr>
            </thead>

            <tbody>
              <?php 
                $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access != 1 ORDER BY id ASC");
                if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)){
                    $id = $row['id'];
                    $username = $row['username'];
                    $firstname = $row['firstname'];
                    $lastname = $row['lastname'];
                    $fullname = $firstname . " " . $lastname;
                    $email = $row['email'];
                    $role = $row['access'];
                    $roleName = get_roleName($role);
                    $status = $row['status'];
                    $statusName = get_statusName($status);
              ?>

              <tr>
                <td class="text-left align-middle"><?php echo $id ?></td>
                <td class="text-left align-middle"><?php echo $fullname?></td>
                <td class="text-left align-middle"><?php echo $username ?></td>
                <td class="text-left align-middle"><?php echo $email?></td>
                <td class="text-left align-middle"><?php echo $roleName ?></td>
                <td class="text-left align-middle"><?php echo $statusName ?></td>
                <td style="table-layout: fixed; width: 15%;">
                  <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                    <input type="hidden" name="id_account" value="<?php echo $id ?>">
                    <input type="submit" class="edit btn btn-sm btn-primary mr-2" value="Edit" name="edit_account">
                    <input type="submit" class="delete btn btn-sm btn-danger" value="Delete" name="delete_account">
                  </form>
                </td>
              </tr>

              <?php 
                  }
                }
              ?>

            </tbody>
          </table>
        </div>        
      </div>
    </div>
  </div>
</div>

<!-- Pop up for Add Account -->
<div class="modal" id="modal_add_account" tabindex="-1" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Add Account</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="close_add_account()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="width: 100%; max-width: 600px;">
        <div class="modal-body">
          <div class="mb-3">
            <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
            <input type="text" name="username" id="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="firstname" class="form-label">First Name <span style="color: red;">*</span></label>
            <input type="text" name="firstname" id="firstname" class="form-control" required title="Last name should only contain letters and spaces." pattern="[A-Za-z\s]{1,50}">
          </div>

          <div class="mb-3">
            <label for="lastname" class="form-label">Last Name <span style="color: red;">*</span></label>
            <input type="text" name="lastname" id="lastname" class="form-control" required title="Last name should only contain letters and spaces.">
            <!-- pattern="[A-Za-z\s]{1,50}" -->
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
            <input type="email" size="30" name="email" id="email" class="form-control" value="<?php echo $row['email'] ?? '' ?>" required title="Enter a valid email address in the format of user@example.com" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$">
          </div>
            
          <div class="mb-2">
            <label for="role" class="form-label">Role <span style="color: red;">*</span></label>
            <select name="role" id="role" class="form-control" required >
                <option value="" hidden></option>
                <option value="2">Requestor</option>
                <option value="3">Editor</option>
                <option value="4">Department Head</option>
                <option value="5">Factory Officer</option>
                <option value="6">QC Supervisor</option>
                <option value="7">Chief Operating Officer</option>
                <!-- <option value="8">Auditor</option> ksama ba to?-->
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <input type="submit" name="add_account" value="Save" class="btn btn-primary pr-3">
          <input type="reset" name="reset" value="Cancel" onclick="close_add_account()" class="btn btn-secondary ml-2">
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Pop up for Edit Account -->
<div class="modal" id="modal_edit_account" tabindex="-1" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Edit Account</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="close_edit_account()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
        if (isset($_SESSION["edit_account_id"])) {
          $id = $_SESSION["edit_account_id"];
          $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE id='$id' ");
          $row = mysqli_fetch_assoc($result);
          
          $role = $row['access'];
          $status = $row['status'];

          $roleName = get_roleName($role);
          $statusName = get_statusName($status);

          echo "<script> 
                  document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('modal_edit_account').style.display = 'block'; 
                    document.body.style.overflow = 'hidden';
                  });
                </script>"; 
      ?>

      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="width: 100%; max-width: 600px;">
        <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
          <div class="mb-3">
            <label for="id" class="form-label">ID</label>
            <input type="text" name="id" class="form-control" required value="<?php echo $id ?>" readonly>
          </div>

          <div class="mb-3">
            <label for="username" class="form-label">Username <span style="color: red;">*</span></label>
            <input type="text" name="username" id="username" class="form-control" required value="<?php echo $row['username'] ?? '' ?>">
          </div>

          <div class="mb-3">
            <label for="firstname" class="form-label">First Name <span style="color: red;">*</span></label>
            <input type="text" name="firstname" id="firstname" class="form-control" required value="<?php echo $row['firstname'] ?? '' ?>" title="Last name should only contain letters and spaces." pattern="[A-Za-z\s]{1,50}">
          </div>

          <div class="mb-3">
            <label for="lastname" class="form-label">Last Name <span style="color: red;">*</span></label>
            <input type="text" name="lastname" id="lastname" class="form-control" required value="<?php echo $row['lastname'] ?? '' ?>" title="Last name should only contain letters and spaces." pattern="[A-Za-z\s]{1,50}">
          </div>
 
          <div class="mb-3">
            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
            <input type="email" size="30" name="email" id="email" class="form-control" required value="<?php echo $row['email'] ?? '' ?>" title="Enter a valid email address in the format of user@example.com" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$">
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role <span style="color: red;">*</span></label>
            <select name="role" id="role" class="form-control" required >
                <option value="<?php echo $role ?>" hidden><?php echo $roleName ?></option>
                <option value="2">Requestor</option>
                <option value="3">Editor</option>
                <option value="4">Department Head</option>
                <option value="5">QC Supervisor</option>
                <option value="6">Factory Officer</option>
                <option value="7">Chief Operating Officer</option>
            </select>
          </div>

          <div class="mb-2">
            <label for="status" class="form-label">Status <span style="color: red;">*</span></label>
            <select name="status" id="status" class="form-control" required >
                <option value="<?php echo $status ?>" hidden><?php echo $statusName ?></option>
                <option value="1">Acive</option>
                <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <div class="">
            <input type="submit" name="edit_account_submit" value="Save" class="btn btn-primary pr-3">
            <input type="reset" name="reset" value="Cancel" onclick="close_edit_account()" class="btn btn-secondary ml-2">
          </div>
        </div>
      </form>

      <?php 
          unset($_SESSION["edit_account_id"]);
        }
      ?>

    </div>
  </div>
</div>

<!-- Pop up for Delete Account -->
<div class="modal" id="modal_delete_account" tabindex="-1" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white">Delete Account</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="close_delete_account()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
        if (isset($_SESSION["delete_account_id"])) {
          $id = $_SESSION["delete_account_id"];

          echo "<script> 
                  document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('modal_delete_account').style.display = 'block'; 
                    document.body.style.overflow = 'hidden';
                  });
                </script>";
      ?>

      <div class="modal-body">
        <p class="h5">Are you sure you want to delete this account permanently?</p> 
      </div>

      <div class="modal-footer">
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
          <input type="hidden" name="id" value="<?php echo $id ?>">
          <input type="submit" name="delte_account_submit" value="Confirm" class="submit btn btn-danger pr-3"> 
          <a href="#" onclick="close_delete_account()" class="close_popup btn btn-secondary" style="text-decoration: none;">Cancel</a>
        </form>
      </div>

      <?php 
          unset($_SESSION["delete_account_id"]);
        }
      ?>

    </div>
  </div>
</div>
    

<!-- Pop up for Message -->
<div class="modal" tabindex="-1" id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Notification</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="close_popup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
        if(isset($_SESSION["message"])){
          $message = $_SESSION["message"];
      
          echo "<script> 
                  document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('popup').style.display = 'block'; 
                    document.body.style.overflow = 'hidden'; 
                  }); 
                </script>";
      ?>
      
      <div class="modal-body my-2">
        <p class="h5"> <?php echo $message ?></p>
      </div>

      <?php
          unset($_SESSION["message"]);
        }
      ?>

    </div>
  </div>
</div>

<?php include '../include/footer.php'; ?>

<script>
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });

  function close_add_account(){
    document.getElementById("modal_add_account").style.display = "none";
    document.body.style.overflow = "auto"; 
  }

  function close_edit_account(){
    document.getElementById("modal_edit_account").style.display = "none";
    document.body.style.overflow = "auto"; 
  }

  function close_delete_account(){
    document.getElementById("modal_delete_account").style.display = "none";
    document.body.style.overflow = "auto"; 
  }

  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('close_popup').addEventListener('click', function () {
      document.getElementById('popup').style.display = 'none';
      document.body.style.overflow = 'auto';
    });

    document.getElementById("btn_add_account").addEventListener("click", function() {
      document.getElementById("modal_add_account").style.display = "block";
      document.body.style.overflow = "hidden"; 
    });
  });
</script>