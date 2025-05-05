<?php 
    include '../include/header_approver.php'; 

    $userId = $_SESSION['SESS_USERID'];
    $userAccess = $_SESSION['SESS_LEVEL'];

    // Initialize counts
    $pending_count = 0;
    $approved_count = 0;
    $rejected_count = 0;

    // Define query conditions based on user access level
    if ($userAccess == 4) {
        $pending_condition = "dept_status = 1 AND dept_head_status = 0";
        $approved_condition = "dept_head_status = 1";
        $rejected_condition = "dept_head_status = 2";
    } elseif ($userAccess == 5) {
        $pending_condition = "dept_head_status = 1 AND supervisor_status = 0";
        $approved_condition = "supervisor_status = 1";
        $rejected_condition = "supervisor_status = 2";
    } elseif ($userAccess == 6) {
        $pending_condition = "supervisor_status = 1 AND fac_officer_status = 0";
        $approved_condition = "fac_officer_status = 1";
        $rejected_condition = "fac_officer_status = 2";
    } elseif ($userAccess == 7) {
        $pending_condition = "fac_officer_status = 1 AND coo_status = 0";
        $approved_condition = "coo_status = 1";
        $rejected_condition = "coo_status = 2";
    }

    // Fetch counts dynamically
    $pending_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE $pending_condition AND (tbl_request.dept_id=$userId OR tbl_request.dept_head_id=$userId OR tbl_request.supervisor_id=$userId OR tbl_request.fac_officer_id=$userId OR tbl_request.coo_id=$userId)");
    $pending_count = mysqli_fetch_assoc($pending_count_result)['count'] ?? 0;

    $approved_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE $approved_condition AND (tbl_request.dept_id=$userId OR tbl_request.dept_head_id=$userId OR tbl_request.supervisor_id=$userId OR tbl_request.fac_officer_id=$userId OR tbl_request.coo_id=$userId)");
    $approved_count = mysqli_fetch_assoc($approved_count_result)['count'] ?? 0;

    $rejected_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE $rejected_condition AND (tbl_request.dept_id=$userId OR tbl_request.dept_head_id=$userId OR tbl_request.supervisor_id=$userId OR tbl_request.fac_officer_id=$userId OR tbl_request.coo_id=$userId)");
    $rejected_count = mysqli_fetch_assoc($rejected_count_result)['count'] ?? 0;

    function checkPendingStatus($access){
        if ($access == 4){
            return ['dept_status' => 1, 'dept_head_status' => 0, 'supervisor_status' => 0, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 5){
            return ['dept_status' => 1, 'dept_head_status' => 1, 'supervisor_status' => 0, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 6){
            return ['dept_status' => 1, 'dept_head_status' => 1, 'supervisor_status' => 1, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 7){
            return ['dept_status' => 1, 'dept_head_status' => 1, 'supervisor_status' => 1, 'fac_officer_status' => 1, 'coo_status' => 0];
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

    // Display request form ..............................................................................
    if(isset($_SESSION['request_id']) && isset($_SESSION['response_id'])){
        $request_id = $_SESSION['request_id'];
        $response_id = $_SESSION['response_id'];
        $view_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.id='$request_id' AND tbl_response.id='$response_id'"));

        if($_SESSION['viewer_request'] == 'pending'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_pending();
                    });
                </script>";
        } 
        else if($_SESSION['viewer_request'] == 'approved'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_approved();
                    });
                </script>";
        } 
        else if($_SESSION['viewer_request'] == 'rejected'){
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        display_rejected();
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

    function updateRequest($response_id, $status){
        $access = $_SESSION['SESS_LEVEL'];
        global $conn;

        if ($access == 4){
            mysqli_query($conn, "UPDATE tbl_response SET dept_head_status='$status' WHERE id='$response_id'");
        } elseif ($access == 5){
            mysqli_query($conn, "UPDATE tbl_response SET supervisor_status='$status' WHERE id='$response_id'");
        } elseif ($access == 6){
            mysqli_query($conn, "UPDATE tbl_response SET fac_officer_status='$status' WHERE id='$response_id'");
        } elseif ($access == 7){
            mysqli_query($conn, "UPDATE tbl_response SET coo_status='$status' WHERE id='$response_id'");

            if($status == 1){
                mysqli_query($conn, "UPDATE tbl_audit SET status='1' WHERE response_id='$response_id'");
            } elseif($status == 2){
                mysqli_query($conn, "UPDATE tbl_audit SET status='0' WHERE response_id='$response_id'");
            }
        }

        header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
        ob_end_flush();
        exit();
    }

    function checkStatusApproveNext($response_id){
        global $conn;
        $access = $_SESSION['SESS_LEVEL'] + 1;

        $result = mysqli_query($conn, "SELECT * FROM tbl_response where id='$response_id'  ");
        $row = mysqli_fetch_assoc($result);

        if($access == 5 && $row['supervisor_status'] != 0){
            return "none";
        }
        elseif($access == 6 && $row['fac_officer_status'] != 0){
            return "none";
        }
        elseif($access == 7 && $row['coo_status'] != 0){
            return "none";
        }
        else{
            return "";
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // View request form pending ..............................................................................
        if(isset($_POST['view_pending'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            $_SESSION['viewer_request'] = 'pending';
            
            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        // View request form approved ..............................................................................
        if (isset($_POST['view_approved'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            $_SESSION['viewer_request'] = 'approved';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        // View request form rejected ..............................................................................
        if (isset($_POST['view_rejected'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            $_SESSION['viewer_request'] = 'rejected';

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }

        // Approve request submit ..............................................................................
        if(isset($_POST['approve_request_submit'])){
            $request_id = $_POST['request_id_answered'];
            $response_id = $_POST['response_id_answered'];
            $status = '1';

            updateRequest($response_id, $status);
        }

        // Reject request submit ..............................................................................
        if(isset($_POST['reject_request_submit'])){
            $request_id = $_POST['request_id_answered'];
            $response_id = $_POST['response_id_answered'];
            $status = '2';
            updateRequest($response_id, $status);
        }
    }
?>

<!-- Pending Approvals -->
<div class="container-fluid" id="pending_reports" style="display: block;">   
    <div class="pending_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Pending Approvals</h2>
                
                <div class="btn-group float-right pb-2">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                            <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending Approval <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                            <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Approved Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                            <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_rejected()">Rejected Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $rejected_count; ?></span></button>
                        </div>
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
                                $userId = $_SESSION['SESS_USERID'];
                                $userAccess = $_SESSION['SESS_LEVEL'];
                                $userStatus = checkPendingStatus($userAccess);

                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_status={$userStatus['dept_status']} AND tbl_response.dept_head_status={$userStatus['dept_head_status']} AND tbl_response.supervisor_status={$userStatus['supervisor_status']} AND tbl_response.fac_officer_status={$userStatus['fac_officer_status']} AND tbl_response.coo_status={$userStatus['coo_status']} AND (tbl_request.dept_id=$userId OR tbl_request.dept_head_id=$userId OR tbl_request.supervisor_id=$userId OR tbl_request.fac_officer_id=$userId OR tbl_request.coo_id=$userId)");
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 25%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 25%;"><?php echo !empty($row['dept_id']) ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 10%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_pending" value="View" class="btn btn-primary btn-sm">
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

<!-- Approved Reports -->
<div class="container-fluid" id="approved_reports" style="display: none;">   
    <div class="approved_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Approved Reports</h2>
               
                <div class="btn-group float-right pb-2">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                            <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Approval <span class="badge badge-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                            <button id="display_approved" type="button" class="btn btn-outline-primary active" onclick="display_approved()">Approved Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                            <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_rejected()">Rejected Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $rejected_count; ?></span></button>
                        </div>
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
                                $userId = $_SESSION['SESS_USERID'];
                                $userAccess = $_SESSION['SESS_LEVEL'];
                                $approvedStatus = 1;

                                if($userAccess == 4){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_head_status=$approvedStatus AND tbl_request.dept_head_id=$userId");
                                } elseif ($userAccess == 5){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.supervisor_status=$approvedStatus AND tbl_request.supervisor_id=$userId");
                                } elseif ($userAccess == 6){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.fac_officer_status=$approvedStatus AND tbl_request.fac_officer_id=$userId");
                                } elseif ($userAccess == 7){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.coo_status=$approvedStatus AND tbl_request.coo_id=$userId");
                                }
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['dept_id'] ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 10%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_approved" value="View" class="btn btn-primary btn-sm">
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

<!-- Rejected Reports -->
<div class="container-fluid" id="rejected_reports" style="display: none;">   
    <div class="rejected_dashboard">
        <div class="card shadow mb-2">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Rejected Reports</h2>

                <div class="btn-group float-right pb-2">
                    <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                        <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                            <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Approval <span class="badge badge-primary rounded-circle ml-1"><?php echo $pending_count; ?></span></button>
                            <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Approved Reports <span class="badge badge-primary rounded-circle ml-1"><?php echo $approved_count; ?></span></button>
                            <button id="display_rejected" type="button" class="btn btn-outline-primary active" onclick="display_rejected()">Rejected Reports <span class="badge badge-light text-primary rounded-circle ml-1"><?php echo $rejected_count; ?></span></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rejected_dataTable" width="100%" cellspacing="0">
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
                                $userId = $_SESSION['SESS_USERID'];
                                $userAccess = $_SESSION['SESS_LEVEL'];
                                $rejectedStatus = 2;

                                if($userAccess == 4){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_head_status=$rejectedStatus AND tbl_request.dept_head_id=$userId");
                                } elseif ($userAccess == 5){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.supervisor_status=$rejectedStatus AND tbl_request.supervisor_id=$userId");
                                } elseif ($userAccess == 6){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.fac_officer_status=$rejectedStatus AND tbl_request.fac_officer_id=$userId");
                                } elseif ($userAccess == 7){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.coo_status=$rejectedStatus AND tbl_request.coo_id=$userId");
                                }
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['date'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['dept_id'] ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22.5%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 10%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_rejected" value="View" class="btn btn-primary btn-sm">
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

                    <div class="card shadow mb-0 bg-light">
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

                                    <tbody>
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

            <div class="modal-footer" style="display: <?php echo checkStatusApproveNext($view_request['id']) ?>">
                <div class="d-flex mr-2">
                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#approveModal" style="display: <?php echo $_SESSION['viewer_request'] == 'approved' ? 'none' : 'block' ?>;">Approve</button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal" style="display: <?php echo $_SESSION['viewer_request'] == 'rejected' ? 'none' : 'block' ?>;">Reject</button>
                </div>
            </div> 
        </div>
    </div>    
</div>

<!-- Pop up for Approve report -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-white" id="exampleModalLabel">Trouble Report</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="h5">Are you sure you want to <b>Approve</b> this report?</p> 
            </div>

            <div class="modal-footer">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <input type="hidden" name="request_id_answered" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id_answered" value="<?php echo $view_request['id'] ?>">

                    <input type="submit" name="approve_request_submit" value="Confirm" class="submit btn btn-success pr-3"> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pop up for Reject report -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel">Trouble Report</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="h5">Are you sure you want to <b>Reject</b> this report?</p> 
            </div>

            <div class="modal-footer">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <input type="hidden" name="request_id_answered" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id_answered" value="<?php echo $view_request['id'] ?>">

                    <input type="submit" name="reject_request_submit" value="Confirm" class="submit btn btn-danger pr-3"> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
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
        // Initialize DataTables for all tables
        $('#pending_dataTable').DataTable();
        $('#approved_dataTable').DataTable();
        $('#rejected_dataTable').DataTable();
    });

    function display_pending() {
        document.getElementById("pending_reports").style.display = "block";
        document.getElementById("approved_reports").style.display = "none";
        document.getElementById("rejected_reports").style.display = "none";
        document.getElementById("display_pending").classList.add('active');
        document.getElementById("display_approved").classList.remove('active');
        document.getElementById("display_rejected").classList.remove('active');
    }

    function display_approved() {
        document.getElementById("pending_reports").style.display = "none";
        document.getElementById("approved_reports").style.display = "block";
        document.getElementById("rejected_reports").style.display = "none";
        document.getElementById("display_pending").classList.remove('active');
        document.getElementById("display_approved").classList.add('active');
        document.getElementById("display_rejected").classList.remove('active');
    }

    function display_rejected() {
        document.getElementById("pending_reports").style.display = "none";
        document.getElementById("approved_reports").style.display = "none";
        document.getElementById("rejected_reports").style.display = "block";
        document.getElementById("display_pending").classList.remove('active');
        document.getElementById("display_approved").classList.remove('active');
        document.getElementById("display_rejected").classList.add('active');
    }

    function closeView() {
        document.getElementById("view_ongoing").style.display = "none";
        document.body.style.overflow = 'auto';
    }
</script>