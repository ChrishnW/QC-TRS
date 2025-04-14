<?php include '../include/header_filer.php'; ?>

<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <div class="col-md-12">
                    <h5 class="float-left">Request Trouble Report</h1>
                    <br>
                </div>                           
            </div>

            <div class="card-body">
                <form action="">
                    <div class="form-group">
                        <label for="request_id">Request ID</label>
                        <input type="text" class="form-control" id="request_id" name="request_id" placeholder="Enter Request ID" required>
                    </div>
                </form>   
            </div>    
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>