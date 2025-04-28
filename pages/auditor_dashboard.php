<?php 
    include '../include/header_auditor.php'; 

    // do not touch! 🤚
    // $pending_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 0");
    // $pending_count = mysqli_fetch_assoc($pending_count_result)['count'] ?? 0;

    // $approved_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 1");
    // $approved_count = mysqli_fetch_assoc($approved_count_result)['count'] ?? 0;
    
    // $closed_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 2");
    // $closed_count = mysqli_fetch_assoc($closed_count_result)['count'] ?? 0;
    
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
<!-- <div class="container-fluid">
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
</div> -->

<!-- Pending Audits -->
<div class="container-fluid" id="pending_audits" style="display: block;">
    <div class="pending_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Audit Reports</h2>
                
                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo 1 ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited <span class="badge badge-primary rounded-circle ml-1"><?php echo 2 ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed <span class="badge badge-primary rounded-circle ml-1"><?php echo 3 ?></span></button>
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
                                            <input type="submit" name="view_pending" class="btn btn-primary mr-2" value="View">
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
                
                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending  <span class="badge badge-primary rounded-circle ml-1"><?php echo 1 ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary active" onclick="display_approved()">Audited <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo 2 ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed <span class="badge badge-primary rounded-circle ml-1"><?php echo 3 ?></span></button>
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

                
                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending  <span class="badge badge-primary rounded-circle ml-1"><?php echo 1 ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited <span class="badge badge-primary rounded-circle ml-1"><?php echo 2 ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary active" onclick="display_closed()">Closed <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo 3 ?></span></button>
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

<!-- View Trouble Report Request Form -->
<div class="modal" tabindex="-1" id="view_ongoing" class="position-fixed" style="display: block; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white">Trouble Report Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeView()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container-fluid justify-content-center align-items-center">
                    <div class="card shadow mb-2 bg-light">
                        <div class="col">
                            <div class="card text-center my-2">
                                <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                            </div>             
                        </div>

                        <div class="container-fluid row">
                            <div class="card col mb-2">
                                <div class="row align-items-center mt-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                    <div class="col-auto">
                                        <img src="<?php echo $view_request['img_ng'] ?? '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                    </div>                 
                                </div>

                                <br>

                                <div class="row align-items-center mb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                    <img src="<?php echo $view_request['img_g'] ?? '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                </div>
                            </div>

                            <div class="container-fluid mr-n5 col d-flex flex-column align-items-stretch">
                                <div class="card col mb-2 flex-grow-1">
                                    <div class="p-2">
                                        <h6><b>Date: </b> <?php echo $view_request['date'] ?? '' ?></h6>                
                                        <h6><b>Model: </b> <?php echo $view_request['model'] ?? '' ?></h6>
                                        <h6><b>Department: </b> <?php echo isset($view_request['dept_id']) ? getUsername($view_request['dept_id']) : '' ?></h6>            
                                        <h6><b>Lot No. </b> <?php echo $view_request['lot'] ?? '' ?></h6>
                                        <h6><b>Serial No. </b> <?php echo $view_request['serial'] ?? '' ?></h6>
                                        <h6><b>Temp No. </b> <?php echo $view_request['temp'] ?? '' ?></h6>    
                                        <h6><b>Quantity: </b> <?php echo $view_request['qty'] ?? '' ?></h6>   
                                    </div>       
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 120px; overflow-y: auto;">
                                    <div class="p-2">
                                        <h6><b>Findings: </b> <?php echo $view_request['findings'] ?? '' ?></h6>
                                    </div>
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 150px;"> 
                                    <div class="p-2">                 
                                        <h6><b>Trouble Origin (100%): </b><?php echo $view_request['origin1'] ?? '' ?></h6>
                                        <h6><b>Checked By (200%): </b> <?php echo $view_request['origin2'] ?? '' ?></h6>
                                        <h6><b>Found by (QC): </b> <?php echo $view_request['finder_qc'] ?? '' ?></h6>
                                        <h6><b>Found by (AI): </b> <?php echo $view_request['finder_ai'] ?? '' ?></h6>
                                        <h6><b>Due Date: </b> <?php echo $view_request['due_date'] ?? '' ?></h6>
                                    </div>
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 180px;">
                                    <div class="col p-2">
                                        <h5 class="mt-1 mb-2 ml-n1"><b>Approval</b></h5>

                                        <hr style="margin: 10px 0;">

                                        <div class="row px-2">
                                            <h6><b>Department Head: </b> <?php echo isset($view_request['dept_head_id']) ? getUsername($view_request['dept_head_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo isset($view_request['dept_head_id']) ? getApprovalStatusColor($view_request['dept_head_status']) : '' ?>"><i><?php echo isset($view_request['dept_head_id']) ? getApprovalStatus($view_request['dept_head_status']) : '' ?></i></h6>
                                        </div>

                                        <div class="row px-2">
                                            <h6><b>QC Supervisor: </b> <?php echo isset($view_request['supervisor_id']) ? getUsername($view_request['supervisor_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo isset($view_request['supervisor_id']) ? getApprovalStatusColor($view_request['supervisor_status']) : '' ?>"><i><?php echo isset($view_request['supervisor_id']) ? getApprovalStatus($view_request['supervisor_status']) : '' ?></i></h6>
                                        </div>

                                        <div class="row px-2">
                                            <h6><b>Factory Officer: </b> <?php echo isset($view_request['fac_officer_id']) ? getUsername($view_request['fac_officer_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo isset($view_request['fac_officer_id']) ? getApprovalStatusColor($view_request['fac_officer_status']) : '' ?>"><i><?php echo isset($view_request['fac_officer_id']) ? getApprovalStatus($view_request['fac_officer_status']) : '' ?></i></h6>
                                        </div>

                                        <div class="row px-2">
                                            <h6><b>COO: </b> <?php echo isset($view_request['coo_id']) ? getUsername($view_request['coo_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo isset($view_request['coo_id']) ? getApprovalStatusColor($view_request['coo_status']) : '' ?>"><i><?php echo isset($view_request['coo_id']) ? getApprovalStatus($view_request['coo_status']) : '' ?></i></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
                        
                <div class="container-fluid justify-content-center align-items-center">
                    <div class="card shadow mb-2 bg-light">
                        <!-- Reason -->
                        <div class="col">
                            <div class="card text-center my-2">
                                <span class="my-2" style="font-size: 24px"><b>REASON:</b></span>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Man</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['man'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Method</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['method'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Material</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['material'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['machine'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-2 bg-light">
                        <!-- Correction -->
                        <div class="col">
                            <div class="card text-center my-2">
                                <span class="my-2" style="font-size: 24px"><b>CORRECTION:</b></span>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card" style="width: 98%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['correction'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-2 bg-light">
                        <!-- Corrective Action -->
                        <div class="col">
                            <div class="card text-center my-2">
                                <span class="my-2" style="font-size: 24px"><b>CORRECTIVE ACTION:</b></span>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Man</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['ca_man'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Method</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['ca_method'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Material</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['ca_material'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                    <div class="text-center m-2">
                                        <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                    </div>
                                </div>

                                <div class="card" style="width: 75%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['ca_machine'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-2 bg-light">
                        <!-- Remarks -->
                        <div class="col">
                            <div class="card text-center my-2">
                                <span class="my-2" style="font-size: 24px"><b>REMARKS:</b></span>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card" style="width: 98%;">
                                    <div class="m-2">
                                        <p><?php echo $view_request['remarks'] ?? '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 bg-light">
                        <div class="card m-2">
                            <div class="table-responsive">
                                <table class="table" id="closed_dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="table-layout: fixed; width: 40%;"></th>
                                            <th style="table-layout: fixed; width: 20%;">Findings</th>
                                            <th style="table-layout: fixed; width: 20%;">Remarks</th>
                                            <th style="table-layout: fixed; width: 10%;">Auditor</th>
                                            <th style="table-layout: fixed; width: 10%;">Date</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-justify">
                                        <tr>
                                            <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td>Effectiveness Verification (After 3 months)</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center mr-2">
                    <!-- <input type="hidden" name="request_id" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id" value="<?php echo $view_request['id'] ?>">

                    <input type="submit" name="edit_request" class="btn btn-warning" value="Edit" style="display: <?php echo $_SESSION['viewer_request'] == 'finished' ? 'none' : 'block' ?>;">
                    <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#deleteModal" style="display: <?php echo $_SESSION['viewer_request'] == 'finished' ? 'none' : 'block' ?>;">Delete</button> -->
                    <input type="reset" name="close_view" onclick="closeView()" value="Close" class="btn btn-secondary ml-2">
                </form>
            </div> 
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

    function closeView() {
        document.getElementById("view_ongoing").style.display = "none";
        document.body.style.overflow = 'auto';
    }
</script>