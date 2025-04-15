<?php 
  ob_start();
  include '../include/header_admin.php'; 

  // Display Message ..................................................................................
  if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];

    echo "<script> 
      document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('display_message').innerHTML = '$message'; 
        document.getElementById('popup').style.display = 'block'; 
      }); 
    </script>";

    unset($_SESSION["message"]);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add account ....................................................................................
    if (isset($_POST['add_account'])) {
      $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
      $role = $_POST['role'];
      $pass = 12345;
      $password = password_hash($pass, PASSWORD_DEFAULT);
      $status = 1;

      $result = mysqli_query($conn, "INSERT INTO tbl_account (username, password, access, status) VALUES ('$name', '$password', '$role', '$status')");

      if($result){
          $_SESSION["message"] = "Account added successfully.";
      }
      else{
          $_SESSION["message"] = "Failed to add account.";
      }

      header("Refresh: .3; url = admin_dashboard.php");
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
                <th>Role</th>
                <th>Status</th>
                <th style="width: 170px;">Actions</th>
              </tr>
            </thead>

            <tbody id="account_list">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tbody>
          </table>
        </div>        
      </div>
    </div>
</div>

<div class="modal" id="modal_add_account" tabindex="-1" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-gradient-primary">
        <h5 class="modal-title text-white">Add Account</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="btn_close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="width: 100%; max-width: 600px;">
        <div class="modal-body">
          <div class="mb-3">
            <label for="" class="form-label">Name <span style="color: red;">*</span></label>
            <input type="text" name="name" id="" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role <span style="color: red;">*</span></label>
            <select name="role" id="" class="form-control" required >
                <option value="" hidden></option>
                <option value="2">Filer</option>
                <option value="3">Maker</option>
                <option value="4">Line Leader</option>
                <option value="5">Department Head</option>
                <option value="6">Factory Officer</option>
                <option value="7">Chief Operating Officer</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <input type="submit" name="add_account" value="Save" class="btn btn-primary pr-3">
          <input type="reset" name="reset" value="Cancel" id="cancel_account" class="btn btn-secondary ml-2">
        </div>
      </form>
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
      
      <div class="modal-body my-2">
        <p class="h5" id="display_message"></p>
      </div>
    </div>
  </div>
</div>


<?php include '../include/footer.php'; ?>

<script>

  $(document).ready(function() {
    $('#dataTable').DataTable();
  });

  document.addEventListener("DOMContentLoaded", function() {

    document.getElementById('close_popup').addEventListener('click', function () {
      document.getElementById('popup').style.display = 'none';
    });

    document.getElementById("btn_add_account").addEventListener("click", function() {
      document.getElementById("modal_add_account").style.display = "block";
    });

    document.getElementById("btn_close").addEventListener("click", function() {
      document.getElementById("modal_add_account").style.display = "none";
    });

    document.getElementById("cancel_account").addEventListener("click", function() {
      document.getElementById("modal_add_account").style.display = "none";
    });

  });

</script>