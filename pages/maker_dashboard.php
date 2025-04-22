<?php 
    include '../include/header_maker.php'; 

    function getApprovalStatus($status) {
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

    function getUsername($id){
        global $conn;
        $account = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_account WHERE id='$id'"));
        return $account['username'];
    }

    // Display request form ..............................................................................
    if(isset($_SESSION['request_id']) && isset($_SESSION['response_id'])){
        $request_id = $_SESSION['request_id'];
        $response_id = $_SESSION['response_id'];
        $view_request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.id='$request_id' AND tbl_response.id='$response_id'"));

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
        // if(isset($_POST['edit_request_btn'])){
        //     $_SESSION['request_id'] = $_POST['request_id'];
        //     $_SESSION['response_id'] = $_POST['response_id'];
        //     $_SESSION['viewer_request'] = 'ongoing';

        //     header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
        //     ob_end_flush();
        //     exit();
        // }
    }

?>

<!-- Ongoing Trouble Report -->
<div class="container-fluid" id="ongoing_trouble_report" style="display: block;">
    <div class="filer_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Ongoing Trouble Report</h2>

                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <button id="ongoingBtn" type="button" class="btn btn-outline-primary active" onclick="display_ongoing()">Ongoing</button>
                    <button id="finishedBtn" type="button" class="btn btn-outline-primary" onclick="display_finished()">Finished</button>
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
                                <th class="text-center align-middle border-top-0">Line Leader</th>
                                <th class="text-center align-middle border-top-0">Department Head</th>
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
                                        $line_leader = $row['leader_status'];
                                        $department_head = $row['dept_head_status'];
                                        $factory_officer = $row['factory_status'];
                                        $coo = $row['coo_status'];

                                        $department_status = getApprovalStatus($department);
                                        $line_leader_status = getApprovalStatus($line_leader);
                                        $department_head_status = getApprovalStatus($department_head);
                                        $factory_officer_status = getApprovalStatus($factory_officer);
                                        $coo_status = getApprovalStatus($coo);
                            ?>

                                <tr>
                                    <td class="text-center align-middle"><?php echo $date ?></td>
                                    <td class="text-center align-middle"><?php echo $model ?></td>
                                    <td class="text-center align-middle"><?php echo $department_status ?></td>
                                    <td class="text-center align-middle"><?php echo $line_leader_status ?></td>
                                    <td class="text-center align-middle"><?php echo $department_head_status ?></td>
                                    <td class="text-center align-middle"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle"><?php echo $coo_status ?></td>
                                    <td style="table-layout: fixed; width: 8%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request_ongoing" class="btn btn-primary" value="View">
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
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Finished Trouble Report</h2>

                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <button id="ongoingBtn" type="button" class="btn btn-outline-primary" onclick="display_ongoing()">Ongoing</button>
                    <button id="finishedBtn" type="button" class="btn btn-outline-primary active" onclick="display_finished()">Finished</button>
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
                                <th class="text-center align-middle border-top-0">Line Leader</th>
                                <th class="text-center align-middle border-top-0">Department Head</th>
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
                                        $line_leader = $row['leader_status'];
                                        $department_head = $row['dept_head_status'];
                                        $factory_officer = $row['factory_status'];
                                        $coo = $row['coo_status'];

                                        $department_status = getApprovalStatus($department);
                                        $line_leader_status = getApprovalStatus($line_leader);
                                        $department_head_status = getApprovalStatus($department_head);
                                        $factory_officer_status = getApprovalStatus($factory_officer);
                                        $coo_status = getApprovalStatus($coo);
                            ?>

                                <tr>
                                    <td class="text-center align-middle"><?php echo $date ?></td>
                                    <td class="text-center align-middle"><?php echo $model ?></td>
                                    <td class="text-center align-middle"><?php echo $department_status ?></td>
                                    <td class="text-center align-middle"><?php echo $line_leader_status ?></td>
                                    <td class="text-center align-middle"><?php echo $department_head_status ?></td>
                                    <td class="text-center align-middle"><?php echo $factory_officer_status ?></td>
                                    <td class="text-center align-middle"><?php echo $coo_status ?></td>
                                    <td style="table-layout: fixed; width: 8%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request_finished" class="btn btn-primary" value="View">
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

<!-- Response / Edit Trouble Report Request Form -->
<div class="modal" tabindex="-1" id="reponse_report_form" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeResponse()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                <div class="modal-body mr-n5">
                    <div class="container-fluid">
                        <!-- Reason -->
                        <div class="col ml-n4">
                            <div class="card shadow text-center my-2">
                                <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid row mr-1">
                        <div class="card shadow col mr-2 mt-2">
                            <div class="row align-items-center mt-2">
                                <div class="col-auto">
                                    <img src="<?php echo $response_request['img_g'] ?? '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                </div>
                                <div class="col text-center">
                                    <h3><b>Good</b></h3>
                                </div>
                            </div>
                            <br>
                            <div class="row align-items-center mb-2">
                                <div class="col-auto">
                                    <img src="<?php echo $response_request['img_ng'] ?? '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                                </div>
                                <div class="col text-center">
                                    <h3><b>Not Good</b></h3>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid col mt-2">
                            <div class="card shadow col mb-2">
                                <div class="px-1 pt-2">
                                    <h6><b>Date: </b> <?php echo $response_request['date'] ?? '' ?></h6>                
                                    <h6><b>Model: </b> <?php echo $response_request['model'] ?? '' ?></h6>
                                    <h6><b>Department: </b> <?php echo isset($response_request['dept_id']) ? getUsername($response_request['dept_id']) : '' ?></h6>            
                                    <h6><b>Lot No. </b> <?php echo $response_request['lot'] ?? '' ?></h6>
                                    <h6><b>Serial No. </b> <?php echo $response_request['serial'] ?? '' ?></h6>
                                    <h6><b>Temp No. </b> <?php echo $response_request['temp'] ?? '' ?></h6>    
                                    <h6><b>Quantity: </b> <?php echo $response_request['qty'] ?? '' ?></h6>   
                                </div>       
                            </div>

                            <div class="card shadow col mb-2" style="height: 100px;">
                                <div class="px-1 pt-2">
                                    <h6><b>Findings: </b> <?php echo $response_request['findings'] ?? '' ?></h6>
                                </div>
                            </div>

                            <div class="card shadow col mb-2"> 
                                <div class="px-1 pt-2">               
                                    <h6><b>Trouble Origin (100%): </b><?php echo $response_request['origin1'] ?? '' ?></h6>
                                    <h6><b>Checked By (200%): </b> <?php echo $response_request['origin2'] ?? '' ?></h6>
                                    <h6><b>Found by (QC): </b> <?php echo $response_request['finder_qc'] ?? '' ?></h6>
                                    <h6><b>Found by (AI): </b> <?php echo $response_request['finder_ai'] ?? '' ?></h6>
                                    <h6><b>Due Date: </b> <?php echo $response_request['due_date'] ?? '' ?></h6>
                                </div>
                            </div>

                            <div class="card shadow col">
                                <div class="px-1 pt-2">
                                    <h5 class="mt-1 mb-n1"><b>Approval</b></h5>
                                    <hr>
                                    <h6><b>Line Leader: </b> <?php echo isset($response_request['leader_id']) ? getUsername($response_request['leader_id']) : '' ?></h6>
                                    <h6><b>Department Head: </b> <?php echo isset($response_request['dept_head_id']) ? getUsername($response_request['dept_head_id']) : '' ?></h6>
                                    <h6><b>Factory Officer: </b> <?php echo isset($response_request['fac_officer_id']) ? getUsername($response_request['fac_officer_id']) : '' ?></h6>
                                    <h6><b>COO: </b> <?php echo isset($response_request['coo_id']) ? getUsername($response_request['coo_id']) : '' ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>         

                    <div class="container-fluid">
                        <!-- Reason -->
                        <div class="col ml-n4 mt-3">
                            <div class="card shadow text-center my-2">
                                <h2 class="mt-2"><b>REASON:</b></h2>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="man"><h5><b>Man</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="man" id="man" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['man'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="method"><h5><b>Method</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="method" id="method" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['method'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="material"><h5><b>Material</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="material" id="material" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['material'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="machine"><h5><b>Machine</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="machine" id="machine" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['machine'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Correction -->
                        <div class="col ml-n4 mt-3">
                            <div class="card shadow text-center my-2">
                                <label for="correction"><h2 class="mt-2"><b>CORRECTION:</b></h2></label>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow" style="width: 98%;">
                                    <div class="m-2">
                                        <textarea name="correction" id="correction" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['correction'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Corrective Action -->
                        <div class="col ml-n4 mt-3">
                            <div class="card shadow text-center my-2">
                                <h2 class="mt-2"><b>CORRECTIVE ACTION:</b></h2>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="ca_man"><h5><b>Man</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="ca_man" id="ca_man" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['ca_man'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="ca_method"><h5><b>Method</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="ca_method" id="ca_method" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['ca_method'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="ca_material"><h5><b>Material</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="ca_material" id="ca_material" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['ca_material'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                    <div class="text-center m-2 pt-2">
                                        <label for="ca_machine"><h5><b>Machine</b></h5></label>
                                    </div>
                                </div>

                                <div class="card shadow" style="width: 75%;">
                                    <div class="m-2">
                                        <textarea name="ca_machine" id="ca_machine" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['ca_machine'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col ml-n4 mt-3">
                            <div class="card shadow text-center my-2">
                                <label for="remarks"><h2 class="mt-2"><b>REMARKS:</b></h2></label>
                            </div>

                            <div class="row mb-2 justify-content-center">
                                <div class="card shadow" style="width: 98%;">
                                    <div class="m-2">
                                        <textarea name="remarks" id="remarks" style="width: 100%; height: 100%; border: none;" required><?php echo $response_request['remarks'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" name="Save_response" class="btn btn-success" value="Save">
                    <input type="reset" name="close_view" onclick="closeResponse()" value="Close" class="btn btn-secondary ml-2">
                </div> 

            </form>


            <?php 
                unset($_SESSION['update_request_id']);
                unset($_SESSION['update_response_id']);
            ?>
        
        </div>    
    </div>
</div>


<!-- View Trouble Report Request Form -->
<div class="modal" tabindex="-1" id="view_ongoing" class="position-fixed" style="display: none; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeView()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body mr-n5">
                <div class="container-fluid">
                    <!-- Reason -->
                    <div class="col ml-n4">
                        <div class="card shadow text-center my-2">
                            <h2 class="mt-2"><b>ROOT CAUSE ANALYSIS</b></h2>
                        </div>
                    </div>
                </div>

                <div class="container-fluid row mr-1">
                    <div class="card shadow col mr-2 mt-2">
                        <div class="row align-items-center mt-2">
                            <div class="col-auto">
                                <img src="<?php echo $view_request['img_g'] ?? '../assets/img/img_not_available.png'; ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                            </div>
                            <div class="col text-center">
                                <h3><b>Good</b></h3>
                            </div>
                        </div>
                        <br>
                        <div class="row align-items-center mb-2">
                            <div class="col-auto">
                                <img src="<?php echo $view_request['img_ng'] ?? '../assets/img/img_not_available.png' ?>" height="300px" width="300px" style="object-fit: contain;" alt="Image is not available">
                            </div>
                            <div class="col text-center">
                                <h3><b>Not Good</b></h3>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid col mt-2">
                        <div class="card shadow col mb-2">
                            <div class="px-1 pt-2">
                                <h6><b>Date: </b> <?php echo $view_request['date'] ?? '' ?></h6>                
                                <h6><b>Model: </b> <?php echo $view_request['model'] ?? '' ?></h6>
                                <h6><b>Department: </b> <?php echo isset($view_request['dept_id']) ? getUsername($view_request['dept_id']) : '' ?></h6>            
                                <h6><b>Lot No. </b> <?php echo $view_request['lot'] ?? '' ?></h6>
                                <h6><b>Serial No. </b> <?php echo $view_request['serial'] ?? '' ?></h6>
                                <h6><b>Temp No. </b> <?php echo $view_request['temp'] ?? '' ?></h6>    
                                <h6><b>Quantity: </b> <?php echo $view_request['qty'] ?? '' ?></h6>   
                            </div>       
                        </div>

                        <div class="card shadow col mb-2" style="height: 100px;">
                            <div class="px-1 pt-2">
                                <h6><b>Findings: </b> <?php echo $view_request['findings'] ?? '' ?></h6>
                            </div>
                        </div>

                        <div class="card shadow col mb-2"> 
                            <div class="px-1 pt-2">               
                                <h6><b>Trouble Origin (100%): </b><?php echo $view_request['origin1'] ?? '' ?></h6>
                                <h6><b>Checked By (200%): </b> <?php echo $view_request['origin2'] ?? '' ?></h6>
                                <h6><b>Found by (QC): </b> <?php echo $view_request['finder_qc'] ?? '' ?></h6>
                                <h6><b>Found by (AI): </b> <?php echo $view_request['finder_ai'] ?? '' ?></h6>
                                <h6><b>Due Date: </b> <?php echo $view_request['due_date'] ?? '' ?></h6>
                            </div>
                        </div>

                        <div class="card shadow col">
                            <div class="px-1 pt-2">
                                <h5 class="mt-1 mb-n1"><b>Approval</b></h5>
                                <hr>
                                <h6><b>Line Leader: </b> <?php echo isset($view_request['leader_id']) ? getUsername($view_request['leader_id']) : '' ?></h6>
                                <h6><b>Department Head: </b> <?php echo isset($view_request['dept_head_id']) ? getUsername($view_request['dept_head_id']) : '' ?></h6>
                                <h6><b>Factory Officer: </b> <?php echo isset($view_request['fac_officer_id']) ? getUsername($view_request['fac_officer_id']) : '' ?></h6>
                                <h6><b>COO: </b> <?php echo isset($view_request['coo_id']) ? getUsername($view_request['coo_id']) : '' ?></h6>
                            </div>
                        </div>
                    </div>
                </div>         

                <div class="container-fluid">
                    <!-- Reason -->
                    <div class="col ml-n4 mt-3">
                        <div class="card shadow text-center my-2">
                            <h2 class="mt-2"><b>REASON:</b></h2>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Man</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['man'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Method</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['method'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Material</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['material'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Machine</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['machine'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Correction -->
                    <div class="col ml-n4 mt-3">
                        <div class="card shadow text-center my-2">
                            <h2 class="mt-2"><b>CORRECTION:</b></h2>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow" style="width: 98%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['correction'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Corrective Action -->
                    <div class="col ml-n4 mt-3">
                        <div class="card shadow text-center my-2">
                            <h2 class="mt-2"><b>CORRECTIVE ACTION:</b></h2>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Man</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['ca_man'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Method</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['ca_method'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Material</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['ca_material'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow justify-content-center align-items-center mr-2 pt-2" style="width: 22%;">
                                <div class="text-center m-2">
                                    <h5><b>Machine</b></h5>
                                </div>
                            </div>

                            <div class="card shadow" style="width: 75%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['ca_machine'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col ml-n4 mt-3">
                        <div class="card shadow text-center my-2">
                            <h2 class="mt-2"><b>REMARKS:</b></h2>
                        </div>

                        <div class="row mb-2 justify-content-center">
                            <div class="card shadow" style="width: 98%;">
                                <div class="m-2">
                                    <p><?php echo $view_request['remarks'] ?? '' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                    <input type="hidden" name="request_id" value="<?php echo $view_request['request_id'] ?>">
                    <input type="hidden" name="response_id" value="<?php echo $view_request['id'] ?>">
                
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
</script>