<?php 
    include '../include/header_auditor.php'; 

    $pending_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_audit WHERE status = 1");
    $pending_count = mysqli_fetch_assoc($pending_count_result)['count'] ?? 0;

    $approved_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_audit WHERE status = 2");
    $approved_count = mysqli_fetch_assoc($approved_count_result)['count'] ?? 0;

    $closed_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_audit WHERE status = 3");
    $closed_count = mysqli_fetch_assoc($closed_count_result)['count'] ?? 0;

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

    function getApprovalStatus($status) {
        switch ($status) {
            case 0:
                return '<span class="badge badge-pill badge-warning"><i class="fas fa-clock"></i> Pending</span>';
            case 1:
                return '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Approved</span>';
            case 2:
                return '<span class="badge badge-pill badge-danger"><i class="fas fa-times"></i> Rejected</span>';
            default:
                return '<span class="badge badge-pill badge-secondary"><i class="fas fa-question"></i> Unknown</span>';
        }
    }

    function getApprovalStatusTable($status) {
        switch ($status) {
            case 0:
                return "Pending";
            case 1:
                return "Approved";
            case 2:
                return "Rejected";
            default:
                return "Unknown";
        }
    }

    function getApprovalStatusColor($status) {
        switch ($status) {
            case 0:
                return "text-primary";
            case 1:
                return "text-success";
            case 2:
                return "text-danger";
            default:
                return "text-secondary";
        }
    }

    function displayModule(){
        if($_SESSION['viewer_request'] == 'pending'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_pending();
                    });
                </script>";
        }else if($_SESSION['viewer_request'] == 'audited'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_approved();
                    });
                </script>";
        }else if($_SESSION['viewer_request'] == 'closed'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_closed();
                    });
                </script>";
        }
    }

    // Display view form ..........................................................................................
    if(isset($_SESSION['audit_id'])){
        $audit_id = $_SESSION['audit_id'];
        $result = mysqli_query($conn, "SELECT * FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.id=$audit_id");
        $view_request = mysqli_fetch_assoc($result);
        
        displayModule();

        echo "<script>     
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('view_ongoing').style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        </script>";
    }

    // Display request update form ..............................................................................
    if(isset($_SESSION['response_audit_id'])){
        $audit_id = $_SESSION['response_audit_id'];
        $response_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id INNER JOIN tbl_audit ON tbl_audit.response_id=tbl_response.id WHERE tbl_audit.id='$audit_id'"));

        displayModule();

        echo "<script>     
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('reponse_report_form').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            </script>";
    }

    // Display request update form after 3 months ..............................................................................
    if(isset($_SESSION['response_audit_id_after'])){
        $audit_id = $_SESSION['response_audit_id_after'];
        $response_request_after = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id INNER JOIN tbl_audit ON tbl_audit.response_id=tbl_response.id WHERE tbl_audit.id='$audit_id'"));

        displayModule();

        echo "<script>     
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('reponse_report_form_after').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            </script>";
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // View request form pending ..............................................................................
        if(isset($_POST['view_pending'])){
            $_SESSION['audit_id'] = $_POST['audit_id'];
            $_SESSION['viewer_request'] = 'pending';
            
            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        // View request form aduited ..............................................................................
        if (isset($_POST['view_audited'])){
            $_SESSION['audit_id'] = $_POST['audit_id'];
            $_SESSION['viewer_request'] = 'audited';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        // View request form closed ..............................................................................
        if (isset($_POST['view_closed'])){
            $_SESSION['audit_id'] = $_POST['audit_id'];
            $_SESSION['viewer_request'] = 'closed';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Response the request form ........................................................................
        if(isset($_POST['response_request_btn'])){
            $_SESSION['response_audit_id'] = $_POST['audit_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Response after 3 months the request form ........................................................................
        if(isset($_POST['response_request_btn_after'])){
            $_SESSION['response_audit_id_after'] = $_POST['audit_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Submit response request ........................................................................
        if(isset($_POST['save_response'])){
            $id = $_POST['response_id'];

            $findings = filter_input(INPUT_POST, "au_findings", FILTER_SANITIZE_SPECIAL_CHARS);
            $remarks = filter_input(INPUT_POST, "au_remarks", FILTER_SANITIZE_SPECIAL_CHARS);
            $auditor = filter_input(INPUT_POST, "au_auditor", FILTER_SANITIZE_SPECIAL_CHARS);
            $date = $_POST['au_date'];
            $status = 2;

            mysqli_query($conn, "UPDATE tbl_audit SET auditor_name='$auditor', auditor_findings='$findings', auditor_remarks='$remarks', auditor_date='$date', status='$status' WHERE id='$id'");

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Submit response request ........................................................................
        if(isset($_POST['save_response_after'])){
            $id = $_POST['response_id'];
            $request_id = $_POST['request_id'];

            $findings = filter_input(INPUT_POST, "au_findings_after", FILTER_SANITIZE_SPECIAL_CHARS);
            $remarks = filter_input(INPUT_POST, "au_remarks_after", FILTER_SANITIZE_SPECIAL_CHARS);
            $auditor = filter_input(INPUT_POST, "au_auditor_after", FILTER_SANITIZE_SPECIAL_CHARS);
            $date = $_POST['au_date_after'];
            $status = 3;

            mysqli_query($conn, "UPDATE tbl_audit SET auditor_name_after='$auditor', auditor_findings_after='$findings', auditor_remarks_after='$remarks', auditor_date_after='$date', status='$status' WHERE id='$id'");

            mysqli_query($conn, "UPDATE tbl_request SET status='1' WHERE id='$request_id'");

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }


    }

?>

<!-- Pending Audits -->
<div class="container-fluid" id="pending_audits" style="display: block;">
    <div class="pending_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Audit Reports</h2>
                
                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $closed_count; ?></span></button>
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
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['date']) ? $row['date'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['model']) ? $row['model'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['dept_id']) ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['qty']) ? $row['qty'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 10%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo !empty($row['id']) ? $row['id'] : '' ?>">
                                            <input type="submit" name="view_pending" class="btn btn-primary btn-sm mr-2" value="View">
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
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending  Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary active" onclick="display_approved()">Audited Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_closed()">Closed Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $closed_count; ?></span></button>
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
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['date']) ? $row['date'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['model']) ? $row['model'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['dept_id']) ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['qty']) ? $row['qty'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 10%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo !empty($row['id']) ? $row['id'] : '' ?>">
                                            <input type="submit" name="view_audited" class="btn btn-primary btn-sm mr-2" value="View">
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
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary active" onclick="display_closed()">Closed Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $closed_count; ?></span></button>
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
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['date']) ? $row['date'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['model']) ? $row['model'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 25%;"><?php echo !empty($row['dept_id']) ? getUser($row['dept_id']) : '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo !empty($row['qty']) ? $row['qty'] : '' ?></td>
                                    <td style="table-layout: fixed; width: 10%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="audit_id" value="<?php echo !empty($row['id']) ? $row['id'] : '' ?>">
                                            <input type="submit" name="view_closed" class="btn btn-primary btn-sm mr-2" value="View">
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
<div class="modal" tabindex="-1" id="view_ongoing" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
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
                                        <img src="<?php echo !empty($view_request['img_ng']) ? $view_request['img_ng'] : '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                    </div>                 
                                </div> <br>

                                <div class="row align-items-center mb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                    <img src="<?php echo !empty($view_request['img_g']) ? $view_request['img_g'] : '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                </div>
                            </div>

                            <div class="container-fluid mr-n5 col d-flex flex-column align-items-stretch">
                                <div class="card col mb-2 flex-grow-1">
                                    <div class="p-2">
                                        <h6><b>Date: </b> <?php echo !empty($view_request['date']) ? $view_request['date'] : '' ?></h6>                
                                        <h6><b>Model: </b> <?php echo !empty($view_request['model']) ? $view_request['model'] : '' ?></h6>
                                        <h6><b>Department: </b> <?php echo !empty($view_request['dept_id']) ? getUsername($view_request['dept_id']) : '' ?></h6>            
                                        <h6><b>Lot No. </b> <?php echo !empty($view_request['lot']) ? $view_request['lot'] : '' ?></h6>
                                        <h6><b>Serial No. </b> <?php echo !empty($view_request['serial']) ? $view_request['serial'] : '' ?></h6>
                                        <h6><b>Temp No. </b> <?php echo !empty($view_request['temp']) ? $view_request['temp'] : '' ?></h6>    
                                        <h6><b>Quantity: </b> <?php echo !empty($view_request['qty']) ? $view_request['qty'] : '' ?></h6>   
                                    </div>       
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 120px; overflow-y: auto;">
                                    <div class="p-2">
                                        <h6><b>Findings: </b> <?php echo !empty($view_request['findings']) ? $view_request['findings'] : '' ?></h6>
                                    </div>
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 150px;"> 
                                    <div class="p-2">                 
                                        <h6><b>Trouble Origin (100%): </b><?php echo !empty($view_request['origin1']) ? $view_request['origin1'] : '' ?></h6>
                                        <h6><b>Checked By (200%): </b> <?php echo !empty($view_request['origin2']) ? $view_request['origin2'] : '' ?></h6>
                                        <h6><b>Found by (QC): </b> <?php echo !empty($view_request['finder_qc']) ? $view_request['finder_qc'] : '' ?></h6>
                                        <h6><b>Found by (AI): </b> <?php echo !empty($view_request['finder_ai']) ? $view_request['finder_ai'] : '' ?></h6>
                                        <h6><b>Due Date: </b> <?php echo !empty($view_request['due_date']) ? $view_request['due_date'] : '' ?></h6>
                                    </div>
                                </div>

                                <div class="card col mb-2 flex-grow-1" style="max-height: 180px;">
                                    <div class="col p-2">
                                        <h5 class="mt-1 mb-n1"><b>Approval</b></h5>
                                        <hr>
                                        <div class="row px-2">
                                            <h6><b>Department Head: </b> <?php echo !empty($view_request['dept_head_id']) ? getUsername($view_request['dept_head_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['dept_head_id']) ? getApprovalStatusColor($view_request['dept_head_status']) : '' ?>"><i><?php echo !empty($view_request['dept_head_id']) ? getApprovalStatus($view_request['dept_head_status']) : '' ?></i></h6>
                                        </div>
                                        <div class="row px-2">
                                            <h6><b>QC Supervisor: </b> <?php echo !empty($view_request['supervisor_id']) ? getUsername($view_request['supervisor_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['supervisor_id']) ? getApprovalStatusColor($view_request['supervisor_status']) : '' ?>"><i><?php echo !empty($view_request['supervisor_id']) ? getApprovalStatus($view_request['supervisor_status']) : '' ?></i></h6>
                                        </div>
                                        <div class="row px-2">
                                            <h6><b>Factory Officer: </b> <?php echo !empty($view_request['fac_officer_id']) ? getUsername($view_request['fac_officer_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['fac_officer_id']) ? getApprovalStatusColor($view_request['fac_officer_status']) : '' ?>"><i><?php echo !empty($view_request['fac_officer_id']) ? getApprovalStatus($view_request['fac_officer_status']) : '' ?></i></h6>
                                        </div>
                                        <div class="row px-2">
                                            <h6><b>COO: </b> <?php echo !empty($view_request['coo_id']) ? getUsername($view_request['coo_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['coo_id']) ? getApprovalStatusColor($view_request['coo_status']) : '' ?>"><i><?php echo !empty($view_request['coo_id']) ? getApprovalStatus($view_request['coo_status']) : '' ?></i></h6>
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
                                        <p><?php echo !empty($view_request['man']) ? $view_request['man'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['method']) ? $view_request['method'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['material']) ? $view_request['material'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['machine']) ? $view_request['machine'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['correction']) ? $view_request['correction'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['ca_man']) ? $view_request['ca_man'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['ca_method']) ? $view_request['ca_method'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['ca_material']) ? $view_request['ca_material'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['ca_machine']) ? $view_request['ca_machine'] : '' ?></p>
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
                                        <p><?php echo !empty($view_request['remarks']) ? $view_request['remarks'] : '' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 bg-light">
                        <div class="card m-2">
                            <div class="table-responsive mb-n4">
                                <table class="table table-bordered" id="closed_dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="table-layout: fixed; width: 30%;"></th>
                                            <th style="table-layout: fixed; width: 20%;">Findings</th>
                                            <th style="table-layout: fixed; width: 20%;">Remarks</th>
                                            <th style="table-layout: fixed; width: 17%;">Auditor</th>
                                            <th style="table-layout: fixed; width: 13%;">Date</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-justify">
                                        <tr>
                                            <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_findings']) ? $view_request['auditor_findings'] : '' ?></td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_remarks']) ? $view_request['auditor_remarks'] : '' ?></td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_name']) ? $view_request['auditor_name'] : '' ?></td>
                                            <td class="text-break text-wrap text-center"><?php echo !empty($view_request['auditor_date']) ? $view_request['auditor_date'] : '' ?></td>
                                        </tr>

                                        <tr>
                                            <td>Effectiveness Verification (After 3 months)</td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_findings_after']) ? $view_request['auditor_findings_after'] : '' ?></td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_remarks_after']) ? $view_request['auditor_remarks_after'] : '' ?></td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_name_after']) ? $view_request['auditor_name_after'] : '' ?></td>
                                            <td class="text-break text-wrap text-center"><?php echo !empty($view_request['auditor_date_after']) ? $view_request['auditor_date_after'] : '' ?></td>
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
                    <input type="hidden" name="audit_id" value="<?php echo $_SESSION['audit_id'] ?? '' ?>">
                    <input type="submit" name="response_request_btn" value="Response" class="btn btn-primary ml-2" style="display: <?php echo $_SESSION['viewer_request'] == 'pending' ? 'block' : 'none' ?>;">
                    <input type="submit" name="response_request_btn" value="Edit" class="btn btn-warning ml-2" style="display: <?php echo $_SESSION['viewer_request'] == 'audited' ? 'block' : 'none' ?>;">
                    <input type="submit" name="response_request_btn_after" value="Response" class="btn btn-primary ml-2" style="display: <?php echo $_SESSION['viewer_request'] == 'audited' ? 'block' : 'none' ?>;">
                    <input type="reset" name="close_view" onclick="closeView()" value="Close" class="btn btn-secondary ml-2">
                </form>
            </div> 
        </div>
    </div>    
</div>

<!-- Response / Edit Trouble Report Request Form -->
<div class="modal" tabindex="-1" id="reponse_report_form" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white">Trouble Report Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeResponse()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="container-fluid justify-content-center align-items-center">
                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card  text-center my-2">
                                    <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                                </div>
                            </div>

                            <div class="container-fluid row">
                                <div class="card col mb-2">

                                    <div class="row align-items-center mt-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                        <div class="col-auto">
                                            <img src="<?php echo !empty($response_request['img_ng']) ? $response_request['img_ng'] : '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                        </div>                 
                                    </div> <br>

                                    <div class="row align-items-center mb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                        <img src="<?php echo !empty($response_request['img_g']) ? $response_request['img_g'] : '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                    </div>
                                </div>

                                <div class="container-fluid mr-n5 col d-flex flex-column align-items-stretch">
                                    <div class="card col mb-2 flex-grow-1">
                                        <div class="p-2">
                                            <h6><b>Date: </b> <?php echo !empty($response_request['date']) ? $response_request['date'] : '' ?></h6>                
                                            <h6><b>Model: </b> <?php echo !empty($response_request['model']) ? $response_request['model'] : '' ?></h6>
                                            <h6><b>Department: </b> <?php echo !empty($response_request['dept_id']) ? getUsername($response_request['dept_id']) : '' ?></h6>            
                                            <h6><b>Lot No. </b> <?php echo !empty($response_request['lot']) ? $response_request['lot'] : '' ?></h6>
                                            <h6><b>Serial No. </b> <?php echo !empty($response_request['serial']) ? $response_request['serial'] : '' ?></h6>
                                            <h6><b>Temp No. </b> <?php echo !empty($response_request['temp']) ? $response_request['temp'] : '' ?></h6>    
                                            <h6><b>Quantity: </b> <?php echo !empty($response_request['qty']) ? $response_request['qty'] : '' ?></h6>   
                                        </div>       
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 120px; overflow-y: auto;">
                                        <div class="p-2">
                                            <h6><b>Findings: </b> <?php echo !empty($response_request['findings']) ? $response_request['findings'] : '' ?></h6>
                                        </div>
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 150px;"> 
                                        <div class="p-2">                 
                                            <h6><b>Trouble Origin (100%): </b><?php echo !empty($response_request['origin1']) ? $response_request['origin1'] : '' ?></h6>
                                            <h6><b>Checked By (200%): </b> <?php echo !empty($response_request['origin2']) ? $response_request['origin2'] : '' ?></h6>
                                            <h6><b>Found by (QC): </b> <?php echo !empty($response_request['finder_qc']) ? $response_request['finder_qc'] : '' ?></h6>
                                            <h6><b>Found by (AI): </b> <?php echo !empty($response_request['finder_ai']) ? $response_request['finder_ai'] : '' ?></h6>
                                            <h6><b>Due Date: </b> <?php echo !empty($response_request['due_date']) ? $response_request['due_date'] : '' ?></h6>
                                        </div>
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 180px;">
                                        <div class="col p-2">
                                            <h5 class="mt-1 mb-n1"><b>Approval</b></h5>
                                            <hr>
                                            <div class="row px-2">
                                                <h6><b>Department Head: </b> <?php echo !empty($response_request['dept_head_id']) ? getUsername($response_request['dept_head_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request['dept_head_id']) ? getApprovalStatusColor($response_request['dept_head_status']) : '' ?>"><i><?php echo !empty($response_request['dept_head_id']) ? getApprovalStatus($response_request['dept_head_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>QC Supervisor: </b> <?php echo !empty($response_request['supervisor_id']) ? getUsername($response_request['supervisor_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request['supervisor_id']) ? getApprovalStatusColor($response_request['supervisor_status']) : '' ?>"><i><?php echo !empty($response_request['supervisor_id']) ? getApprovalStatus($response_request['supervisor_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>Factory Officer: </b> <?php echo !empty($response_request['fac_officer_id']) ? getUsername($response_request['fac_officer_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request['fac_officer_id']) ? getApprovalStatusColor($response_request['fac_officer_status']) : '' ?>"><i><?php echo !empty($response_request['fac_officer_id']) ? getApprovalStatus($response_request['fac_officer_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>COO: </b> <?php echo !empty($response_request['coo_id']) ? getUsername($response_request['coo_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request['coo_id']) ? getApprovalStatusColor($response_request['coo_status']) : '' ?>"><i><?php echo !empty($response_request['coo_id']) ? getApprovalStatus($response_request['coo_status']) : '' ?></i></h6>
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

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['man']) ? $response_request['man'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Method</b></span></span>
                                        </div>
                                    </div>

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['method']) ? $response_request['method'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request['material']) ? $response_request['material'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['machine']) ? $response_request['machine'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <!-- Correction -->
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2" style="font-size: 24px"><b>CORRECTION:  </b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['correction']) ? $response_request['correction'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request['ca_man']) ? $response_request['ca_man'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request['ca_method']) ? $response_request['ca_method'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request['ca_material']) ? $response_request['ca_material'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['ca_machine']) ? $response_request['ca_machine'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <!-- Remarks -->
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2 pt-2" style="font-size: 24px"><b>REMARKS:</b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request['remarks']) ? $response_request['remarks'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4 bg-light">
                            <div class="card m-2">
                                <div class="table-responsive mb-n4">
                                    <table class="table table-bordered" id="closed_dataTable" width="100%" cellspacing="0">
                                        <thead class="">
                                            <tr class="text-center">
                                                <th style="table-layout: fixed; width: 30%;"></th>
                                                <th style="table-layout: fixed; width: 20%;" onclick="document.getElementById('au_findings').focus();">Findings <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 20%;" onclick="document.getElementById('au_remarks').focus();">Remarks <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 17%;" onclick="document.getElementById('au_auditor').focus();">Auditor <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 13%;" onclick="document.getElementById('au_date').focus();">Date <span style="color: red;">*</span></b></span></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_findings').focus();">
                                                    <textarea name="au_findings" id="au_findings" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['auditor_findings']) ? $response_request['auditor_findings'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_remarks').focus();">
                                                    <textarea name="au_remarks" id="au_remarks" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['auditor_remarks']) ? $response_request['auditor_remarks'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_auditor').focus();">
                                                    <textarea name="au_auditor" id="au_auditor" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['auditor_name']) ? $response_request['auditor_name'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap text-center" onclick="document.getElementById('au_date').focus();">
                                                    <input type="date" name="au_date" id="au_date" class="form-control border-0" value="<?php echo !empty($response_request['auditor_date']) ? $response_request['auditor_date'] : '' ?>" required>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Effectiveness Verification (After 3 months)</td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request['auditor_findings_after']) ? $response_request['auditor_findings_after'] : '' ?></td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request['auditor_remarks_after']) ? $response_request['auditor_remarks_after'] : '' ?></td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request['auditor_name_after']) ? $response_request['auditor_name_after'] : '' ?></td>
                                                <td class="text-break text-wrap text-center"><?php echo !empty($response_request['auditor_date_after']) ? $response_request['auditor_date_after'] : '' ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                    </div>
            </div>

            <div class="modal-footer">
                    <div class="mr-4">
                        <input type="hidden" name="response_id" value="<?php echo !empty($response_request['id']) ? $response_request['id'] : '' ?>">
                        <input type="submit" name="save_response" class="btn btn-success" value="Save">
                        <input type="reset" name="close_view" onclick="closeResponse()" value="Close" class="btn btn-secondary ml-2">
                    </div>
                </form>
            </div> 

            <?php 
                unset($_SESSION['response_audit_id']);
            ?>
        
        </div>    
    </div>
</div>

<!-- Response Trouble Report Request Form after 3 months -->
<div class="modal" tabindex="-1" id="reponse_report_form_after" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white">Trouble Report Form</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeResponse_after()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="container-fluid justify-content-center align-items-center">
                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card  text-center my-2">
                                    <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                                </div>
                            </div>

                            <div class="container-fluid row">
                                <div class="card col mb-2">

                                    <div class="row align-items-center mt-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                        <div class="col-auto">
                                            <img src="<?php echo !empty($response_request_after['img_ng']) ? $response_request_after['img_ng'] : '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                        </div>                 
                                    </div> <br>

                                    <div class="row align-items-center mb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                        <img src="<?php echo !empty($response_request_after['img_g']) ? $response_request_after['img_g'] : '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                    </div>
                                </div>

                                <div class="container-fluid mr-n5 col d-flex flex-column align-items-stretch">
                                    <div class="card col mb-2 flex-grow-1">
                                        <div class="p-2">
                                            <h6><b>Date: </b> <?php echo !empty($response_request_after['date']) ? $response_request_after['date'] : '' ?></h6>                
                                            <h6><b>Model: </b> <?php echo !empty($response_request_after['model']) ? $response_request_after['model'] : '' ?></h6>
                                            <h6><b>Department: </b> <?php echo !empty($response_request_after['dept_id']) ? getUsername($response_request_after['dept_id']) : '' ?></h6>            
                                            <h6><b>Lot No. </b> <?php echo !empty($response_request_after['lot']) ? $response_request_after['lot'] : '' ?></h6>
                                            <h6><b>Serial No. </b> <?php echo !empty($response_request_after['serial']) ? $response_request_after['serial'] : '' ?></h6>
                                            <h6><b>Temp No. </b> <?php echo !empty($response_request_after['temp']) ? $response_request_after['temp'] : '' ?></h6>    
                                            <h6><b>Quantity: </b> <?php echo !empty($response_request_after['qty']) ? $response_request_after['qty'] : '' ?></h6>   
                                        </div>       
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 120px; overflow-y: auto;">
                                        <div class="p-2">
                                            <h6><b>Findings: </b> <?php echo !empty($response_request_after['findings']) ? $response_request_after['findings'] : '' ?></h6>
                                        </div>
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 150px;"> 
                                        <div class="p-2">                 
                                            <h6><b>Trouble Origin (100%): </b><?php echo !empty($response_request_after['origin1']) ? $response_request_after['origin1'] : '' ?></h6>
                                            <h6><b>Checked By (200%): </b> <?php echo !empty($response_request_after['origin2']) ? $response_request_after['origin2'] : '' ?></h6>
                                            <h6><b>Found by (QC): </b> <?php echo !empty($response_request_after['finder_qc']) ? $response_request_after['finder_qc'] : '' ?></h6>
                                            <h6><b>Found by (AI): </b> <?php echo !empty($response_request_after['finder_ai']) ? $response_request_after['finder_ai'] : '' ?></h6>
                                            <h6><b>Due Date: </b> <?php echo !empty($response_request_after['due_date']) ? $response_request_after['due_date'] : '' ?></h6>
                                        </div>
                                    </div>

                                    <div class="card col mb-2 flex-grow-1" style="max-height: 180px;">
                                        <div class="col p-2">
                                            <h5 class="mt-1 mb-n1"><b>Approval</b></h5>
                                            <hr>
                                            <div class="row px-2">
                                                <h6><b>Department Head: </b> <?php echo !empty($response_request_after['dept_head_id']) ? getUsername($response_request_after['dept_head_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request_after['dept_head_id']) ? getApprovalStatusColor($response_request_after['dept_head_status']) : '' ?>"><i><?php echo !empty($response_request_after['dept_head_id']) ? getApprovalStatus($response_request_after['dept_head_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>QC Supervisor: </b> <?php echo !empty($response_request_after['supervisor_id']) ? getUsername($response_request_after['supervisor_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request_after['supervisor_id']) ? getApprovalStatusColor($response_request_after['supervisor_status']) : '' ?>"><i><?php echo !empty($response_request_after['supervisor_id']) ? getApprovalStatus($response_request_after['supervisor_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>Factory Officer: </b> <?php echo !empty($response_request_after['fac_officer_id']) ? getUsername($response_request_after['fac_officer_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request_after['fac_officer_id']) ? getApprovalStatusColor($response_request_after['fac_officer_status']) : '' ?>"><i><?php echo !empty($response_request_after['fac_officer_id']) ? getApprovalStatus($response_request_after['fac_officer_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>COO: </b> <?php echo !empty($response_request_after['coo_id']) ? getUsername($response_request_after['coo_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo !empty($response_request_after['coo_id']) ? getApprovalStatusColor($response_request_after['coo_status']) : '' ?>"><i><?php echo !empty($response_request_after['coo_id']) ? getApprovalStatus($response_request_after['coo_status']) : '' ?></i></h6>
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

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['man']) ? $response_request_after['man'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Method</b></span></span>
                                        </div>
                                    </div>

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['method']) ? $response_request_after['method'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request_after['material']) ? $response_request_after['material'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['machine']) ? $response_request_after['machine'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <!-- Correction -->
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2" style="font-size: 24px"><b>CORRECTION:  </b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['correction']) ? $response_request_after['correction'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request_after['ca_man']) ? $response_request_after['ca_man'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request_after['ca_method']) ? $response_request_after['ca_method'] : '' ?></p>
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
                                            <p><?php echo !empty($response_request_after['ca_material']) ? $response_request_after['ca_material'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <span class="text-center" style="font-size: 18px;"><b>Machine</b></span>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['ca_machine']) ? $response_request_after['ca_machine'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <!-- Remarks -->
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2 pt-2" style="font-size: 24px"><b>REMARKS:</b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <p><?php echo !empty($response_request_after['remarks']) ? $response_request_after['remarks'] : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4 bg-light">
                            <div class="card m-2">
                                <div class="table-responsive mb-n4">
                                    <table class="table table-bordered" id="closed_dataTable" width="100%" cellspacing="0">
                                        <thead class="">
                                            <tr class="text-center">
                                                <th style="table-layout: fixed; width: 30%;"></th>
                                                <th style="table-layout: fixed; width: 20%;" onclick="document.getElementById('au_findings_after').focus();">Findings <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 20%;" onclick="document.getElementById('au_remarks_after').focus();">Remarks <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 17%;" onclick="document.getElementById('au_auditor_after').focus();">Auditor <span style="color: red;">*</span></b></span></th>
                                                <th style="table-layout: fixed; width: 13%;" onclick="document.getElementById('au_date_after').focus();">Date <span style="color: red;">*</span></b></span></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request_after['auditor_findings']) ? $response_request_after['auditor_findings'] : '' ?></td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request_after['auditor_remarks']) ? $response_request_after['auditor_remarks'] : '' ?></td>
                                                <td class="text-break text-wrap"><?php echo !empty($response_request_after['auditor_name']) ? $response_request_after['auditor_name'] : '' ?></td>
                                                <td class="text-break text-wrap text-center"><?php echo !empty($response_request_after['auditor_date']) ? $response_request_after['auditor_date'] : '' ?></td>
                                            </tr>

                                            <tr>
                                                <td>Effectiveness Verification (After 3 months)</td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_findings_after').focus();">
                                                    <textarea name="au_findings_after" id="au_findings_after"  class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request_after['auditor_findings_after']) ? $response_request_after['auditor_findings_after'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_remarks_after').focus();">
                                                    <textarea name="au_remarks_after" id="au_remarks_after"  class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request_after['auditor_remarks_after']) ? $response_request_after['auditor_remarks_after'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap" onclick="document.getElementById('au_auditor_after').focus();">
                                                    <textarea name="au_auditor_after" id="au_auditor_after"  class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request_after['auditor_name_after']) ? $response_request_after['auditor_name_after'] : '' ?></textarea>
                                                </td>
                                                <td class="text-break text-wrap text-center" onclick="document.getElementById('au_date_after').focus();">
                                                    <input type="date" name="au_date_after" id="au_date_after" class="form-control border-0" value="<?php echo !empty($response_request_after['auditor_date_after']) ? $response_request_after['auditor_date_after'] : '' ?>" required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                    </div>
            </div>

            <div class="modal-footer">
                    <div class="mr-4">
                        <input type="hidden" name="response_id" value="<?php echo !empty($response_request_after['id']) ? $response_request_after['id'] : '' ?>">
                        <input type="hidden" name="request_id" value="<?php echo !empty($response_request_after['request_id']) ? $response_request_after['request_id'] : '' ?>">
                        <input type="submit" name="save_response_after" class="btn btn-success" value="Save">
                        <input type="reset" name="close_view" onclick="closeResponse_after()" value="Close" class="btn btn-secondary ml-2">
                    </div>
                </form>
            </div> 

            <?php 
                unset($_SESSION['response_audit_id_after']);
            ?>
        
        </div>    
    </div>
</div>

<?php 
    include '../include/footer.php'; 
    unset($_SESSION['audit_id']);
?>

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
    
    function closeResponse() {
        document.getElementById("reponse_report_form").style.display = "none";
        document.body.style.overflow = 'auto';
    }

    function closeResponse_after() {
        document.getElementById("reponse_report_form_after").style.display = "none";
        document.body.style.overflow = 'auto';
    }
</script>