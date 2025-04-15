<?php include '../include/header_admin.php'; ?>

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
                <th>Username</th>
                <th>Department</th>
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

      <div class="modal-body">
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" style="width: 100%; max-width: 600px;">
          

        <!-- Insert here label and input tags that is needed for adding new accounts -->


      </div>

          <div class="modal-footer">
            <input type="submit" name="add_account" value="Save" class="btn btn-primary pr-3" disabled>
            <input type="reset" name="reset" value="Cancel" id="cancel_account" class="btn btn-secondary ml-2">
          </div>

        </form>
    </div>
  </div>
</div>


<?php include '../include/footer.php'; ?>

<script>

  document.addEventListener("DOMContentLoaded", function() {

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