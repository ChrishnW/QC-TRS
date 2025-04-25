<?php 
    include '../include/header_auditor.php'; 
    
    function getUsername($id){
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE id = '$id'");
        if ($result) {
                $row = mysqli_fetch_assoc($result);
            return $row['firstname'] . " " . $row['lastname'];
        } else {
            return null;
        }
    }

?>

<!-- Dashboard Cards -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Temporary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                        </div>

                        <div class="col-auto">
                            <i class="fa fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Temporary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                        </div>

                        <div class="col-auto">
                            <i class="fa fa-check-circle fa-2x text-gray-300"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Temporary</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                        </div>

                        <div class="col-auto">
                            <i class="fa fa-times-circle fa-2x text-gray-300"></i> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Audits -->
<div class="container-fluid" id="pending_audits" style="display: block;">
    <div class="pending_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Audit Reports</h2>
                
                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending Audit</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="pending_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>Model</th>
                                <th>Department</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php 
                                $result = mysqli_query($conn, "SELECT tbl_request.date, tbl_request.model, tbl_request.dept_id, tbl_request.qty, tbl_audit.id FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.status=1");
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                                <tr>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 18%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo $row['id'] ?? '' ?>">
                                            <input type="submit" name="view_pending" class="btn btn-primary mr-2" value="View" disabled>
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

<!-- Approved Audits -->
<div class="container-fluid" id="approved_audits" style="display: none;">
    <div class="approved_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Audited Reports</h2>
                
                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Audit</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary active" onclick="display_approved()">Audited</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="approved_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>Model</th>
                                <th>Department</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>

                            <?php 
                                $result = mysqli_query($conn, "SELECT tbl_request.date, tbl_request.model, tbl_request.dept_id, tbl_request.qty, tbl_audit.id FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.status=2");
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                                <tr>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 18%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo $row['id'] ?? '' ?>">
                                            <input type="submit" name="view_pending" class="btn btn-primary mr-2" value="View" disabled>
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

<!-- Closed Audits -->
<div class="container-fluid" id="closed_audits" style="display: none;">
    <div class="closed_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Closed Reports</h2>

                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Audit</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary active" onclick="display_closed()">Closed</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="closed_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>Model</th>
                                <th>Department</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php 
                                $result = mysqli_query($conn, "SELECT tbl_request.date, tbl_request.model, tbl_request.dept_id, tbl_request.qty, tbl_audit.id FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.status=3");
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                                <tr>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 18%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo $row['id'] ?? '' ?>">
                                            <input type="submit" name="view_pending" class="btn btn-primary mr-2" value="View" disabled>
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

<!-- Trouble Report Form -->
<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4 bg-light">
                <h2 class="float-left">Trouble Report Form</h2>
                <br>                         
            </div>

            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                <div class="card-body mx-3">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date">Date <span style="color: red;">*</span></label><br>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="model">Model <span style="color: red;">*</span></label><br>
                            <input type="text" name="model" id="model" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="department">Department <span style="color: red;">*</span></label><br>
                            <select name="department"  id="department" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=3 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="lot">Lot No. <span style="color: red;">*</span></label>
                            <input type="text" name="lot" id="lot" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label for="serial">Serial No. <span style="color: red;">*</span></label><br>
                            <input type="text" name="serial" id="serial" class="form-control" required>
                        </div>  

                        <div class="col-md-3">
                            <label for="temp">Temp No. <span style="color: red;">*</span></label><br>
                            <input type="number" name="temp" id="temp" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label for="quantity">Quantity <span style="color: red;">*</span></label><br>
                            <input type="number" name="quantity" id="quantity"  class="form-control" required min="0">
                        </div>  
                    </div>

                    <hr class="mt-4">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="findings">Findings <span style="color: red;">*</span></label><br>
                            <textarea name="findings" id="findings" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="origin">Trouble Origin (100%) <span style="color: red;">*</span></label><br>
                            <input type="text" name="origin" id="origin" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="check">Checked by (200%) <span style="color: red;">*</span></label><br>
                            <input type="text" name="check" id="check" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="found_qc">Found by (QC) <span style="color: red;">*</span></label><br>
                            <input type="text" name="found_qc" id="found_qc" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="found_ai">Found by (AI) <span style="color: red;">*</span></label><br>
                            <input type="text" name="found_ai" id="found_ai" class="form-control" required>
                        </div>
                    </div>                   

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="image_not_good">Image (Not Good) <span style="color: red;">*</span></label><br>
                            <input type="file" name="image_not_good" id="image_not_good" class="form-control" style="height: auto;" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div class="col-md-6">
                            <label for="image_good">Image (Good) <span style="color: red;">*</span></label><br>
                            <input type="file" name="image_good" id="image_good" class="form-control" style="height: auto;" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="due_date">Due Date <span style="color: red;">*</span></label><br>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <h5 class="mb-2">Approval</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="leader">Department Head <span style="color: red;">*</span></label><br>
                            <select name="leader" id="leader" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=4 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>
                                
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="head">QC Supervisor <span style="color: red;">*</span></label><br>
                            <select name="head" id="head" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=5 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">  
                        <div class="col-md-6">
                            <label for="officer">Factory Officer <span style="color: red;">*</span></label><br>
                            <select name="officer" id="officer" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=6 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="coo">Chief Operating Officer (COO) <span style="color: red;">*</span></label><br>
                            <select name="coo" id="coo" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=7 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" name="request_submit" value="Submit" class="btn btn-primary">
                    <input type="reset" value="Reset" class="btn btn-secondary mr-3">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#pending_dataTable').DataTable();
        $('#approved_dataTable').DataTable();
        $('#closed_dataTable').DataTable();
    });

    // Function to display pending audits
    function display_pending() {
        document.getElementById("pending_audits").style.display = "block";
        document.getElementById("approved_audits").style.display = "none";
        document.getElementById("closed_audits").style.display = "none";
        document.getElementById("display_pending").classList.add('active');
        document.getElementById("display_approved").classList.remove('active');
        document.getElementById("display_rejected").classList.remove('active');
    }

    // Function to display approved audits
    function display_approved() {
        document.getElementById("pending_audits").style.display = "none";
        document.getElementById("approved_audits").style.display = "block";
        document.getElementById("closed_audits").style.display = "none";
        document.getElementById("display_pending").classList.remove('active');
        document.getElementById("display_approved").classList.add('active');
        document.getElementById("display_rejected").classList.remove('active');
    }

    // Function to display closed audits
    function display_closed() {
        document.getElementById("pending_audits").style.display = "none";
        document.getElementById("approved_audits").style.display = "none";
        document.getElementById("closed_audits").style.display = "block";
        document.getElementById("display_pending").classList.remove('active');
        document.getElementById("display_approved").classList.remove('active');
        document.getElementById("display_rejected").classList.add('active');
    }
</script>