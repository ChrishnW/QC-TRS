<?php 
    include '../include/header_editor.php'; 

    $dept_id = $_SESSION['SESS_USERID'];

    $ongoing_count = 0;
    $finished_count = 0;

    $ongoing_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 0 AND dept_id = '$dept_id'");
    $ongoing_count = mysqli_fetch_assoc($ongoing_count_result)['count'] ?? 0;

    $finished_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 1 AND dept_id = '$dept_id'");
    $finished_count = mysqli_fetch_assoc($finished_count_result)['count'] ?? 0;

    function getApprovalStatus($status) {
        switch ($status) {
            case 0:
                return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>';
            case 1:
                return '<span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>';
            case 2:
                return '<span class="badge badge-danger"><i class="fas fa-times"></i> Rejected</span>';
            default:
                return '<span class="badge badge-secondary"><i class="fas fa-question"></i> Unknown</span>';
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


    function getDeptStatus($status) {
        switch ($status) {
            case 0:
                return "Pending";
            case 1:
                return "Submitted";
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

    // Display request form ..............................................................................
    if(isset($_SESSION['request_id']) && isset($_SESSION['response_id'])){
        $request_id = $_SESSION['request_id'];
        $response_id = $_SESSION['response_id'];
        $view_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id INNER JOIN tbl_audit ON tbl_audit.response_id=tbl_response.id WHERE tbl_request.id='$request_id' AND tbl_response.id='$response_id'"));

        if($_SESSION['viewer_request'] == 'ongoing'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_ongoing();
                    });
                </script>";
        }else if($_SESSION['viewer_request'] == 'finished'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_finished();
                    });
                </script>";
        }
        
        echo "<script>     
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('view_ongoing').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            </script>";
    }

    // Display request update form ..............................................................................
    if(isset($_SESSION['update_request_id']) && isset($_SESSION['update_response_id'])){
        $request_id = $_SESSION['update_request_id'];
        $response_id = $_SESSION['update_response_id'];
        $response_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id INNER JOIN tbl_audit ON tbl_audit.response_id=tbl_response.id WHERE tbl_request.id='$request_id' AND tbl_response.id='$response_id'"));

        echo "<script>     
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('reponse_report_form').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            </script>";
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // View the request form ongoing ........................................................................
        if(isset($_POST['view_request_ongoing'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            $_SESSION['viewer_request'] = 'ongoing';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // View the request form finished ........................................................................
        if(isset($_POST['view_request_finished'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            $_SESSION['viewer_request'] = 'finished';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Response the request form ........................................................................
        if(isset($_POST['response_request_btn'])){
            $_SESSION['update_request_id'] = $_POST['request_id'];
            $_SESSION['update_response_id'] = $_POST['response_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Edit the request form ongoing ........................................................................
        if(isset($_POST['edit_request_btn'])){
            $_SESSION['update_request_id'] = $_POST['request_id'];
            $_SESSION['update_response_id'] = $_POST['response_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Update submit form ........................................................................
        if(isset($_POST['save_response'])){
            $response_id = $_POST['response_id'];

            $man = filter_input(INPUT_POST, "man", FILTER_SANITIZE_SPECIAL_CHARS);
            $method = filter_input(INPUT_POST, "method", FILTER_SANITIZE_SPECIAL_CHARS);
            $material = filter_input(INPUT_POST, "material", FILTER_SANITIZE_SPECIAL_CHARS);
            $machine = filter_input(INPUT_POST, "machine", FILTER_SANITIZE_SPECIAL_CHARS);
            $correction = filter_input(INPUT_POST, "correction", FILTER_SANITIZE_SPECIAL_CHARS);
            $ca_man = filter_input(INPUT_POST, "ca_man", FILTER_SANITIZE_SPECIAL_CHARS);
            $ca_method = filter_input(INPUT_POST, "ca_method", FILTER_SANITIZE_SPECIAL_CHARS);
            $ca_material = filter_input(INPUT_POST, "ca_material", FILTER_SANITIZE_SPECIAL_CHARS);
            $ca_machine = filter_input(INPUT_POST, "ca_machine", FILTER_SANITIZE_SPECIAL_CHARS);
            $remarks = filter_input(INPUT_POST, "remarks", FILTER_SANITIZE_SPECIAL_CHARS);
            $dept_status = 1;

            $result = mysqli_query($conn, "UPDATE tbl_response SET man='$man', method='$method', material='$material', machine='$machine', correction='$correction', ca_man='$ca_man', ca_method='$ca_method', ca_material='$ca_material', ca_machine='$ca_machine', remarks='$remarks', dept_status='$dept_status' WHERE id='$response_id'");

            if($result){
                $_SESSION['message'] = "Response successfully submitted!";
            }else{
                $_SESSION['message'] = "Failed to submit response!";
            }

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }
    }
?>

<!-- Ongoing Trouble Report -->
<div class="container-fluid" id="ongoing_trouble_report" style="display: block;">
    <div class="filer_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Ongoing Trouble Report</h2>

                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="ongoingBtn" type="button" class="btn btn-outline-primary active" onclick="display_ongoing()">Ongoing Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $ongoing_count; ?></span></button>
                        <button id="finishedBtn" type="button" class="btn btn-outline-primary" onclick="display_finished()">Finished Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $finished_count; ?></span></button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="ongoing_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Date</th>
                                <th rowspan="2" class="text-center align-middle">Model</th>
                                <th rowspan="2" class="text-center align-middle">Department</th>
                                <th colspan="4" class="text-center align-middle">Approval Status</th>
                                <th rowspan="2" class="text-center align-middle">Actions</th>
                            </tr>
                            <tr>
                                <th class="text-center align-middle border-top-0">Department Head</th>
                                <th class="text-center align-middle border-top-0">QC Supervisor</th>
                                <th class="text-center align-middle border-top-0">Factory Officer</th>
                                <th class="text-center align-middle border-top-0">COO</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $dept_id = $_SESSION['SESS_USERID'];
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=0 AND tbl_request.dept_id='$dept_id'"); 
                                if(mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $request_id = $row['request_id'];
                                        $response_id = $row['id'];

                                        $date = $row['date'];
                                        $model = $row['model'];
                                        $department = $row['dept_status'];
                                        $line_leader = $row['dept_head_status'];
                                        $department_head = $row['supervisor_status'];
                                        $factory_officer = $row['fac_officer_status'];
                                        $coo = $row['coo_status'];

                                        $department_status = getDeptStatus($department);
                                        $line_leader_status = getApprovalStatusTable($line_leader);
                                        $department_head_status = getApprovalStatusTable($department_head);
                                        $factory_officer_status = getApprovalStatusTable($factory_officer);
                                        $coo_status = getApprovalStatusTable($coo);
                            ?>

                                <tr>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $date ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $model ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 12%;"><?php echo $department_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $line_leader_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $department_head_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 8%;"><?php echo $coo_status ?></td>
                                    <td style="table-layout: fixed; width: 8%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request_ongoing" class="btn btn-primary btn-sm" value="View">
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

<!-- Finished Trouble Report -->
<div class="container-fluid" id="finished_trouble_report" style="display: none;">
    <div class="filer_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Finished Trouble Report</h2>

                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <button id="ongoingBtn" type="button" class="btn btn-outline-primary" onclick="display_ongoing()">Ongoing Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $ongoing_count; ?></span></button>
                        <button id="finishedBtn" type="button" class="btn btn-outline-primary active" onclick="display_finished()">Finished Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $finished_count; ?></span></button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="finished_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Date</th>
                                <th rowspan="2" class="text-center align-middle">Model</th>
                                <th rowspan="2" class="text-center align-middle">Department</th>
                                <th colspan="4" class="text-center align-middle">Approval Status</th>
                                <th rowspan="2" class="text-center align-middle">Actions</th>
                            </tr>
                            <tr>
                                <th class="text-center align-middle border-top-0">Department Head</th>
                                <th class="text-center align-middle border-top-0">QC Supervisor</th>
                                <th class="text-center align-middle border-top-0">Factory Officer</th>
                                <th class="text-center align-middle border-top-0">COO</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=1 AND tbl_request.dept_id='$dept_id'"); 
                                if(mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $request_id = $row['request_id'];
                                        $response_id = $row['id'];

                                        $date = $row['date'];
                                        $model = $row['model'];
                                        $department = $row['dept_status'];
                                        $line_leader = $row['dept_head_status'];
                                        $department_head = $row['supervisor_status'];
                                        $factory_officer = $row['fac_officer_status'];
                                        $coo = $row['coo_status'];

                                        $department_status = getDeptStatus($department);
                                        $line_leader_status = getApprovalStatusTable($line_leader);
                                        $department_head_status = getApprovalStatusTable($department_head);
                                        $factory_officer_status = getApprovalStatusTable($factory_officer);
                                        $coo_status = getApprovalStatusTable($coo);
                            ?>

                                <tr>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $date ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $model ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 12%;"><?php echo $department_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 15%;"><?php echo $line_leader_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $department_head_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 8%;"><?php echo $coo_status ?></td>
                                    <td style="table-layout: fixed; width: 8%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request_finished" class="btn btn-primary btn-sm" value="View">
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
                            <div class="card  text-center my-2">
                                <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                            </div>
                        </div>

                        <div class="container-fluid row">
                            <div class="card col mb-2">
                                <div class="row align-items-center mt-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                    <div class="col-auto">
                                        <img src="<?php echo !empty($view_request['img_ng']) ? $view_request['img_ng'] : '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                    </div>                 
                                </div>

                                <br>
                                
                                <div class="row align-items-center mb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
                                    <img src="<?php echo !empty($view_request['img_g']) ? $view_request['img_g'] : '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                </div>
                            </div>

                            <div class="container-fluid mr-n5 col d-flex flex-column align-items-stretch">
                                <div class="card col mb-2 flex-grow-1">
                                    <div class="p-2">
                                        <h6><b>Date: </b> <?php echo !empty($view_request['date']) ? $view_request['date'] : '' ?></h6>                
                                        <h6><b>Model: </b> <?php echo !empty($view_request['model']) ? $view_request['model'] : '' ?></h6>
                                        <h6><b>Department: </b> <?php echo isset($view_request['dept_id']) ? getUsername($view_request['dept_id']) : '' ?></h6>            
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

                    <div class="card shadow mb-2 bg-light">
                        <div class="card m-2">
                            <div class="table-responsive table-bordered mb-n4">
                                <table class="table" id="closed_dataTable" width="100%" cellspacing="0">
                                    <thead class="">
                                        <tr class="text-center">
                                            <th style="table-layout: fixed; width: 30%;"></th>
                                            <th style="table-layout: fixed; width: 20%;">Findings</th>
                                            <th style="table-layout: fixed; width: 20%;">Remarks</th>
                                            <th style="table-layout: fixed; width: 15%;">Auditor</th>
                                            <th style="table-layout: fixed; width: 15%;">Date</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-justify">
                                        <tr>
                                            <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                            <td><?php echo !empty($view_request['auditor_findings']) ? $view_request['auditor_findings'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_remarks']) ? $view_request['auditor_remarks'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_name']) ? $view_request['auditor_name'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_date']) ? $view_request['auditor_date'] : '' ?></td>
                                        </tr>

                                        <tr>
                                            <td>Effectiveness Verification (After 3 months)</td>
                                            <td><?php echo !empty($view_request['auditor_findings_after']) ? $view_request['auditor_findings_after'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_remarks_after']) ? $view_request['auditor_remarks_after'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_name_after']) ? $view_request['auditor_name_after'] : '' ?></td>
                                            <td><?php echo !empty($view_request['auditor_date_after']) ? $view_request['auditor_date_after'] : '' ?></td>
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
                    <input type="hidden" name="request_id" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id" value="<?php echo $view_request['response_id'] ?>">
                
                    <input type="submit" name="edit_request_btn" class="btn btn-warning" value="Edit" style="display: <?php echo $_SESSION['viewer_request'] == 'ongoing' && ($view_request['dept_status'] == 1 || $view_request['dept_status'] == 2) ? 'block' : 'none' ?>;">
                    <input type="submit" name="response_request_btn" class="btn btn-primary" value="Response" style="display: <?php echo $_SESSION['viewer_request'] == 'ongoing' && $view_request['dept_status'] == 0 ? 'block' : 'none' ?>;">
                    <input type="reset" name="close_view" onclick="closeView()" value="Close" class="btn btn-secondary ml-2">
                </form>
            </div> 

            <?php 
                unset($_SESSION['request_id']);
                unset($_SESSION['response_id']);
                unset($_SESSION['viewer_request']);
            ?>
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
                                                <h6><b>Department Head: </b> <?php echo isset($response_request['dept_head_id']) ? getUsername($response_request['dept_head_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo isset($response_request['dept_head_id']) ? getApprovalStatusColor($response_request['dept_head_status']) : '' ?>"><i><?php echo isset($response_request['dept_head_id']) ? getApprovalStatus($response_request['dept_head_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>QC Supervisor: </b> <?php echo isset($response_request['supervisor_id']) ? getUsername($response_request['supervisor_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo isset($response_request['supervisor_id']) ? getApprovalStatusColor($response_request['supervisor_status']) : '' ?>"><i><?php echo isset($response_request['supervisor_id']) ? getApprovalStatus($response_request['supervisor_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>Factory Officer: </b> <?php echo isset($response_request['fac_officer_id']) ? getUsername($response_request['fac_officer_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo isset($response_request['fac_officer_id']) ? getApprovalStatusColor($response_request['fac_officer_status']) : '' ?>"><i><?php echo isset($response_request['fac_officer_id']) ? getApprovalStatus($response_request['fac_officer_status']) : '' ?></i></h6>
                                            </div>
                                            <div class="row px-2">
                                                <h6><b>COO: </b> <?php echo isset($response_request['coo_id']) ? getUsername($response_request['coo_id']) : '' ?></h6>
                                                <h6 class="ml-3 <?php echo isset($response_request['coo_id']) ? getApprovalStatusColor($response_request['coo_status']) : '' ?>"><i><?php echo isset($response_request['coo_id']) ? getApprovalStatus($response_request['coo_status']) : '' ?></i></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>       
                    </div>  

                    <div class="container-fluid justify-content-center align-items-center">
                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2" style="font-size: 24px"><b>REASON:</b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="man"><span class="text-center" style="font-size: 18px;"><b>Man <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="man" id="man" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['man']) ? $response_request['man'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="method"><span class="text-center" style="font-size: 18px;"><b>Method <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card " style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="method" id="method" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['method']) ? $response_request['method'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="material"><span class="text-center" style="font-size: 18px;"><b>Material <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="material" id="material" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['material']) ? $response_request['material'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card  justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="machine"><span class="text-center" style="font-size: 18px;"><b>Machine <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="machine" id="machine" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['machine']) ? $response_request['machine'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card text-center my-2">
                                    <label for="correction" class="pt-2"><span class="my-2" style="font-size: 24px"><b>CORRECTION: <span style="color: red;">*</span>  </b></span></label>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <textarea name="correction" id="correction" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['correction']) ? $response_request['correction'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card text-center my-2">
                                    <span class="my-2" style="font-size: 24px"><b>CORRECTIVE ACTION:</b></span>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="ca_man"><span class="text-center" style="font-size: 18px;"><b>Man <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="ca_man" id="ca_man" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['ca_man']) ? $response_request['ca_man'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="ca_method"><span class="text-center" style="font-size: 18px;"><b>Method <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="ca_method" id="ca_method" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['ca_method']) ? $response_request['ca_method'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="ca_material"><span class="text-center" style="font-size: 18px;"><b>Material <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="ca_material" id="ca_material" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['ca_material']) ? $response_request['ca_material'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                        <div class="text-center m-2">
                                            <label for="ca_machine"><span class="text-center" style="font-size: 18px;"><b>Machine <span style="color: red;">*</span></b></span></label>
                                        </div>
                                    </div>

                                    <div class="card" style="width: 75%;">
                                        <div class="m-2">
                                            <textarea name="ca_machine" id="ca_machine" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['ca_machine']) ? $response_request['ca_machine'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-2 bg-light">
                            <div class="col">
                                <div class="card text-center my-2">
                                    <label for="remarks"><span class="my-2 pt-2" style="font-size: 24px"><b>REMARKS: <span style="color: red;">*</span></b></span></label>
                                </div>

                                <div class="row mb-2 justify-content-center">
                                    <div class="card " style="width: 98%;">
                                        <div class="m-2">
                                            <textarea name="remarks" id="remarks" class="form-control border-0" style="width: 100%; height: 100%; color: black;" required><?php echo !empty($response_request['remarks']) ? $response_request['remarks'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-0 bg-light">
                            <div class="card m-2">
                                <div class="table-responsive">
                                    <table class="table" id="closed_dataTable" width="100%" cellspacing="0">
                                        <thead class="">
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
                                                <td><?php echo !empty($response_request['auditor_findings']) ? $response_request['auditor_findings'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_remarks']) ? $response_request['auditor_remarks'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_name']) ? $response_request['auditor_name'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_date']) ? $response_request['auditor_date'] : '' ?></td>
                                            </tr>

                                            <tr>
                                                <td>Effectiveness Verification (After 3 months)</td>
                                                <td><?php echo !empty($response_request['auditor_findings_after']) ? $response_request['auditor_findings_after'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_remarks_after']) ? $response_request['auditor_remarks_after'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_name_after']) ? $response_request['auditor_name_after'] : '' ?></td>
                                                <td><?php echo !empty($response_request['auditor_date_after']) ? $response_request['auditor_date_after'] : '' ?></td>
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
                        <input type="hidden" name="response_id" value="<?php echo $response_request['response_id'] ?>">
                        <input type="submit" name="save_response" class="btn btn-success" value="Save">
                        <input type="reset" name="close_view" onclick="closeResponse()" value="Close" class="btn btn-secondary ml-2">
                    </div>
                </form>
            </div> 

            <?php 
                unset($_SESSION['update_request_id']);
                unset($_SESSION['update_response_id']);
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
        $('#ongoing_dataTable').DataTable();
        $('#finished_dataTable').DataTable();
    });

    function display_ongoing() {
        document.getElementById("ongoing_trouble_report").style.display = "block";
        document.getElementById("finished_trouble_report").style.display = "none";
        document.getElementById("ongoingBtn").classList.add('active');
        document.getElementById("finishedBtn").classList.remove('active');
    }

    function display_finished() {
        document.getElementById("ongoing_trouble_report").style.display = "none";
        document.getElementById("finished_trouble_report").style.display = "block";
        document.getElementById("finishedBtn").classList.add('active');
        document.getElementById("ongoingBtn").classList.remove('active');
    }

    function closeView() {
        document.getElementById("view_ongoing").style.display = "none";
        document.body.style.overflow = 'auto';
    }

    function closeResponse() {
        document.getElementById("reponse_report_form").style.display = "none";
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('close_popup').addEventListener('click', function () {
            document.getElementById('popup').style.display = 'none';
        });
    });
</script>