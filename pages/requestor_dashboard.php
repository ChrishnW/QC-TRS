<?php 
    include '../include/header_requestor.php'; 

    $ongoing_count = 0;
    $finished_count = 0;

    $ongoing_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 0");
    $ongoing_count = mysqli_fetch_assoc($ongoing_count_result)['count'] ?? 0;

    $finished_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request WHERE status = 1");
    $finished_count = mysqli_fetch_assoc($finished_count_result)['count'] ?? 0;

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

    function checkIfEditorResponse($response_id) {
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM tbl_response WHERE id='$response_id'");
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            return empty($row['man']) ? '' : 'none';
        }
        return 'none';
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
        $response_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.id='$request_id' AND tbl_response.id='$response_id'"));

        echo "<script>     
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('edit_ongoing').style.display = 'block';
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

        // Delete account ............................................................................................................
        if(isset($_POST['delete_account_submit'])) {
            $request_id = $_POST['request_id'];
            $response_id = $_POST['response_id'];

            $result_request = mysqli_query($conn, "DELETE FROM tbl_request WHERE id='$request_id'");
            $result_response = mysqli_query($conn, "DELETE FROM tbl_response WHERE id='$response_id'");
            $result_audit = mysqli_query($conn, "DELETE FROM tbl_audit WHERE response_id='$response_id'");

            if($result_request && $result_response && $result_audit) {
                $_SESSION["message"] = "Report deleted successfully.";
            } else {
                $_SESSION["message"] = "Failed to delete Report. Please try again.";
            }

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit;
        }

        // Edit account ............................................................................................................
        if(isset($_POST['edit_request'])) {
            $_SESSION['update_request_id'] = $_POST['request_id'];
            $_SESSION['update_response_id'] = $_POST['response_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit;
        }

        // Edit request form submit ................................................................................................
        if(isset($_POST['update_request_submit'])){
            $request_id = $_POST['update_request_id'];

            $date = $_POST['date'];
            $department = $_POST['department'];
            $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_SPECIAL_CHARS);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
            $lot = filter_input(INPUT_POST, 'lot', FILTER_SANITIZE_NUMBER_INT);
            $serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_NUMBER_INT);
            $temp = filter_input(INPUT_POST, 'temp', FILTER_SANITIZE_NUMBER_INT);
            $findings = filter_input(INPUT_POST, 'findings', FILTER_SANITIZE_SPECIAL_CHARS);
            $origin1 = filter_input(INPUT_POST, 'origin', FILTER_SANITIZE_SPECIAL_CHARS);
            $origin2 = filter_input(INPUT_POST, 'check', FILTER_SANITIZE_SPECIAL_CHARS);
            $found_qc = filter_input(INPUT_POST, 'found_qc', FILTER_SANITIZE_SPECIAL_CHARS);
            $found_ai = filter_input(INPUT_POST, 'found_ai', FILTER_SANITIZE_SPECIAL_CHARS);
            $due_date = $_POST['due_date'];
            $leader = $_POST['leader'];
            $head = $_POST['head'];
            $officer = $_POST['officer'];
            $coo = $_POST['coo'];

            if(isset($_FILES["image_good"]) && $_FILES['image_good']['error'] == 0 && isset($_FILES["image_not_good"]) && $_FILES['image_not_good']['error'] == 0) {
                $image_good_raw = $_FILES["image_good"]["name"];
                $image_good = str_replace(" ", "_", $image_good_raw);
                $image_good_path = "IMG/GOOD/" . $image_good;
                $img_temp_path_good = $_FILES["image_good"]["tmp_name"];
    
                move_uploaded_file($img_temp_path_good, $image_good_path);
    
                $image_notgood_raw = $_FILES["image_not_good"]["name"];
                $image_notgood = str_replace(" ", "_", $image_notgood_raw);
                $image_notgood_path = "IMG/NOTGOOD/" . $image_notgood;
                $img_temp_path_notgood = $_FILES["image_not_good"]["tmp_name"];
    
                move_uploaded_file($img_temp_path_notgood, $image_notgood_path);

                $result = mysqli_query($conn, "UPDATE tbl_request SET date='$date', model='$model', lot='$lot', serial='$serial', temp='$temp', findings='$findings', origin1='$origin1', origin2='$origin2', finder_qc='$found_qc', finder_ai='$found_ai', qty='$quantity', img_ng='$image_notgood_path', img_g='$image_good_path', due_date='$due_date', dept_id='$department', dept_head_id='$leader', supervisor_id='$head', fac_officer_id='$officer', coo_id='$coo' WHERE id='$request_id'");

                if($result) {
                    $_SESSION["message"] = "Request updated successfully.";

                    // $editor_email = getUsername($department);
                    // $subject = "QC Trouble Report Request";
                    // $body = "A new trouble report has been submitted. Please review it.";
                    // mail($editor_email, $subject, $body,);

                } else {
                    $_SESSION["message"] = "Failed to update request. Please try again.";
                }
            } else {
                $_SESSION["message"] = "Failed to upload images. Please try again.";
            }

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit;
        }
    }
?>

<!-- Ongoing Trouble Report -->
<div class="container-fluid" id="ongoing_trouble_report" style="display: block;">
    <div class="filer_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Ongoing Trouble Report Request</h2>

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
                        <thead class="bg-primary text-white" style="height: 15px;">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Date</th>
                                <th rowspan="2" class="text-center align-middle">Model</th>
                                <th rowspan="2" class="text-center align-middle">Department</th>
                                <th colspan="4" class="text-center align-middle">Approval Status</th>
                                <th rowspan="2" class="text-center align-middle">Actions</th>
                            </tr>
                            <tr> 
                                <th class="text-center align-middle border-top-0">Department Head</th>
                                <th class="text-center align-middle border-top-0">Factory Officer</th>
                                <th class="text-center align-middle border-top-0">QC Supervisor</th>
                                <th class="text-center align-middle border-top-0">COO</th>
                            </tr>
                        </thead>

                        <tbody>               
                            <?php
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=0"); 
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
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $department_head_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 8%;"><?php echo $coo_status ?></td>
                                    <td style="table-layout: fixed; width: 8%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request_ongoing" class="btn btn-primary btn-sm" value="View" >
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
                <h2 class="float-left">Finished Trouble Report Request</h2>

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
                                <th class="text-center align-middle border-top-0">Factory Officer</th>
                                <th class="text-center align-middle border-top-0">QC Supervisor</th>
                                <th class="text-center align-middle border-top-0">COO</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=1"); 
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
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle" style="table-layout: fixed; width: 20%;"><?php echo $department_head_status ?></td>
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
                                            <h6><b>Factory Officer: </b> <?php echo !empty($view_request['fac_officer_id']) ? getUsername($view_request['fac_officer_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['fac_officer_id']) ? getApprovalStatusColor($view_request['fac_officer_status']) : '' ?>"><i><?php echo !empty($view_request['fac_officer_id']) ? getApprovalStatus($view_request['fac_officer_status']) : '' ?></i></h6>
                                        </div>
                                        <div class="row px-2">
                                            <h6><b>QC Supervisor: </b> <?php echo !empty($view_request['supervisor_id']) ? getUsername($view_request['supervisor_id']) : '' ?></h6>
                                            <h6 class="ml-3 <?php echo !empty($view_request['supervisor_id']) ? getApprovalStatusColor($view_request['supervisor_status']) : '' ?>"><i><?php echo !empty($view_request['supervisor_id']) ? getApprovalStatus($view_request['supervisor_status']) : '' ?></i></h6>
                                        </div>
                                        <div class="row px-2">
                                            <h6><b>Chief Operating Officer: </b> <?php echo !empty($view_request['coo_id']) ? getUsername($view_request['coo_id']) : '' ?></h6>
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

                    <div class="card shadow mb-0 bg-light">
                        <div class="card m-2">
                            <div class="table-responsive table-bordered mb-n4">
                                <table class="table" id="closed_dataTable" width="100%" cellspacing="0">
                                    <thead class="">
                                        <tr class="text-center">
                                            <th style="table-layout: fixed; width: 30%;"></th>
                                            <th style="table-layout: fixed; width: 20%;">Findings</th>
                                            <th style="table-layout: fixed; width: 20%;">Remarks</th>
                                            <th style="table-layout: fixed; width: 17%;">Auditor</th>
                                            <th style="table-layout: fixed; width: 13%;">Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Implementation Verification (as stated in the corrective action or after received the Root cause analysis report)</td>
                                            <td class="text-break text-wrap text-justify"><?php echo !empty($view_request['auditor_findings']) ? $view_request['auditor_findings'] : '' ?></td>
                                            <td class="text-break text-wrap text-justify"><?php echo !empty($view_request['auditor_remarks']) ? $view_request['auditor_remarks'] : '' ?></td>
                                            <td class="text-break text-wrap"><?php echo !empty($view_request['auditor_name']) ? $view_request['auditor_name'] : '' ?></td>
                                            <td class="text-break text-wrap text-center"><?php echo !empty($view_request['auditor_date']) ? $view_request['auditor_date'] : '' ?></td>
                                        </tr>

                                        <tr>
                                            <td>Effectiveness Verification (After 3 months)</td>
                                            <td class="text-break text-wrap text-justify"><?php echo !empty($view_request['auditor_findings_after']) ? $view_request['auditor_findings_after'] : '' ?></td>
                                            <td class="text-break text-wrap text-justify"><?php echo !empty($view_request['auditor_remarks_after']) ? $view_request['auditor_remarks_after'] : '' ?></td>
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

            <div class="modal-footer" style="display: <?php echo checkIfEditorResponse($view_request['response_id']) ?>;">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center mr-2">
                    <input type="hidden" name="request_id" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id" value="<?php echo $view_request['response_id'] ?>">

                    <input type="submit" name="edit_request" class="btn btn-warning" value="Edit" style="display: <?php echo $_SESSION['viewer_request'] == 'finished' ? 'none' : 'block' ?>;">
                    <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#deleteModal" style="display: <?php echo $_SESSION['viewer_request'] == 'finished' ? 'none' : 'block' ?>;">Delete</button>
                    <input type="reset" name="close_view" onclick="closeView()" value="Close" class="btn btn-secondary ml-2">
                </form>
            </div> 
        </div>
    </div>    
</div>

<!-- Edit Trouble Report Request Form -->
<div class="modal" tabindex="-1" id="edit_ongoing" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white">Edit Trouble Request</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="close_edit_modal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                    <div class="container-fluid">
                        <div class="card shadow">
                            <div class="card-body mx-3">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="date">Date <span style="color: red;">*</span></label><br>
                                        <input type="date" name="date" id="date" class="form-control" value="<?php echo !empty($response_request['date']) ? $response_request['date'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="model">Model <span style="color: red;">*</span></label><br>
                                        <input type="text" name="model" id="model" class="form-control" value="<?php echo !empty($response_request['model']) ? $response_request['model'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="department">Department <span style="color: red;">*</span></label><br>
                                        <select name="department"  id="department" class="form-control" required >

                                            <option value="<?php echo !empty($response_request['dept_id']) ? $response_request['dept_id']: '' ?>" hidden><?php echo !empty($response_request['dept_id']) ? getUsername($response_request['dept_id']) : '' ?></option>

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
                                        <input type="text" name="lot" id="lot" class="form-control" value="<?php echo !empty($response_request['lot']) ? $response_request['lot'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="serial">Serial No. <span style="color: red;">*</span></label><br>
                                        <input type="text" name="serial" id="serial" class="form-control" value="<?php echo !empty($response_request['serial']) ? $response_request['serial'] : '' ?>" required>
                                    </div>  

                                    <div class="col-md-3">
                                        <label for="temp">Temp No. <span style="color: red;">*</span></label><br>
                                        <input type="number" name="temp" id="temp" class="form-control" value="<?php echo !empty($response_request['temp']) ? $response_request['temp'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="quantity">Quantity <span style="color: red;">*</span></label><br>
                                        <input type="number" name="quantity" id="quantity"  class="form-control" value="<?php echo !empty($response_request['qty']) ? $response_request['qty'] : '' ?>" required min="0">
                                    </div>  
                                </div>

                                <hr class="mt-4">

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="findings">Findings <span style="color: red;">*</span></label><br>
                                        <textarea name="findings" id="findings" class="form-control" rows="5" required><?php echo !empty($response_request['findings']) ? $response_request['findings'] : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="origin">Trouble Origin (100%) <span style="color: red;">*</span></label><br>
                                        <input type="text" name="origin" id="origin" class="form-control" value="<?php echo !empty($response_request['origin1']) ? $response_request['origin1'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="check">Checked by (200%) <span style="color: red;">*</span></label><br>
                                        <input type="text" name="check" id="check" class="form-control" value="<?php echo !empty($response_request['origin2']) ? $response_request['origin2'] : '' ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="found_qc">Found by (QC) <span style="color: red;">*</span></label><br>
                                        <input type="text" name="found_qc" id="found_qc" class="form-control" value="<?php echo !empty($response_request['finder_qc']) ? $response_request['finder_qc'] : '' ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="found_ai">Found by (AI) <span style="color: red;">*</span></label><br>
                                        <input type="text" name="found_ai" id="found_ai" class="form-control" value="<?php echo !empty($response_request['finder_ai']) ? $response_request['finder_ai'] : '' ?>" required>
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
                                        <input type="date" name="due_date" id="due_date" class="form-control" value="<?php echo !empty($response_request['due_date']) ? $response_request['due_date'] : '' ?>" required>
                                    </div>
                                </div>

                                <hr class="mt-4">

                                <h5 class="mb-2">Approval</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="leader">Department Head <span style="color: red;">*</span></label><br>
                                        <select name="leader" id="leader" class="form-control" required >

                                            <option value="<?php echo !empty($response_request['dept_head_id']) ? $response_request['dept_head_id'] : '' ?>" hidden><?php echo !empty($response_request['dept_head_id']) ? getUsername($response_request['dept_head_id']) : '' ?></option>

                                            <?php 
                                                $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=4 AND status=1");
                                                if($result) {    
                                                    while($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                            <?php 
                                                    }
                                                }
                                            ?>
                                            
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="officer">Factory Officer <span style="color: red;">*</span></label><br>
                                        <select name="officer" id="officer" class="form-control" required >
                                            
                                            <option value="<?php echo !empty($response_request['fac_officer_id']) ? $response_request['fac_officer_id'] : '' ?>" hidden><?php echo !empty($response_request['fac_officer_id']) ? getUsername($response_request['fac_officer_id']) : '' ?></option>

                                            <?php 
                                                $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=6 AND status=1");
                                                if($result) {    
                                                    while($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                            <?php 
                                                    }
                                                }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">  
                                    <div class="col-md-6">
                                        <label for="head">QC Supervisor <span style="color: red;">*</span></label><br>
                                        <select name="head" id="head" class="form-control" required >
                                            
                                            <option value="<?php echo !empty($response_request['supervisor_id']) ? $response_request['supervisor_id'] : '' ?>" hidden><?php echo !empty($response_request['supervisor_id']) ? getUsername($response_request['supervisor_id']) : '' ?></option>
                                            
                                            <?php 
                                                $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=5 AND status=1");
                                                if($result) {    
                                                    while($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                            <?php 
                                                    }
                                                }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="coo">Chief Operating Officer (COO) <span style="color: red;">*</span></label><br>
                                        <select name="coo" id="coo" class="form-control" required >
                                            
                                            <option value="<?php echo !empty($response_request['coo_id']) ? $response_request['coo_id'] : '' ?>" hidden><?php echo !empty($response_request['coo_id']) ? getUsername($response_request['coo_id']) : '' ?></option>

                                            <?php 
                                                $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=7 AND status=1");
                                                if($result) {    
                                                    while($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                            <?php 
                                                    }
                                                }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>               
                    </div>
            </div>

            <div class="modal-footer">
                    <input type="hidden" name="update_request_id" value="<?php echo $response_request['request_id'] ?>">
                    <input type="submit" name="update_request_submit" value="Submit" class="btn btn-primary">
                    <input type="reset" value="Cancel" onclick="close_edit_modal()" class="btn btn-secondary mr-3">
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
    unset($_SESSION['update_request_id']);
    unset($_SESSION['update_response_id']);
?>

<!-- Pop up for Delete Account -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Trouble Report</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="h5">Are you sure you want to delete this report permanently?</p> 
            </div>

            <div class="modal-footer">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <input type="hidden" name="request_id" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id" value="<?php echo $view_request['response_id'] ?>">

                    <input type="submit" name="delete_account_submit" value="Confirm" class="submit btn btn-danger pr-3"> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <!-- <a href="#" onclick="close_delete_modal()" class="close_popup btn btn-secondary" style="text-decoration: none;">Cancel</a> -->
                </form>
            </div>
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

<?php 
    unset($_SESSION['request_id']);
    unset($_SESSION['response_id']);
    unset($_SESSION['viewer_request']);
?>

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

    function close_delete_modal(){
        document.getElementById("modal_delete_account").style.display = "none";
        document.body.style.overflow = 'auto';
    }

    function open_delete_modal(){
        document.getElementById("modal_delete_account").style.display = "block";
        document.body.style.overflow = 'hidden';
    }

    function close_edit_modal(){
        document.getElementById("edit_ongoing").style.display = "none";
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('close_popup').addEventListener('click', function () {
            document.getElementById('popup').style.display = 'none';
        });
    });
</script>