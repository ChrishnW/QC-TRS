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


<?php include '../include/footer.php'; ?>