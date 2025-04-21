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
    }
</script>