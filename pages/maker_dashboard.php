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
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=0"); 
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
                                    <td><?php echo $date ?></td>
                                    <td><?php echo $model ?></td>
                                    <td><?php echo $department_status ?></td>
                                    <td><?php echo $line_leader_status ?></td>
                                    <td><?php echo $department_head_status ?></td>
                                    <td><?php echo $factory_officer_status ?></td>
                                    <td><?php echo $coo_status ?></td>
                                    <td>
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request" class="btn btn-primary" value="View" disabled>
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
                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response ON tbl_request.id=tbl_response.request_id WHERE tbl_request.status=1"); 
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
                                    <td><?php echo $date ?></td>
                                    <td><?php echo $model ?></td>
                                    <td><?php echo $department_status ?></td>
                                    <td><?php echo $line_leader_status ?></td>
                                    <td><?php echo $department_head_status ?></td>
                                    <td><?php echo $factory_officer_status ?></td>
                                    <td><?php echo $coo_status ?></td>
                                    <td>
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                                            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                                            <input type="hidden" name="response_id" value="<?php echo $response_id; ?>">
                                        
                                            <input type="submit" name="view_request" class="btn btn-primary" value="View">
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

<!-- View Ongoing Trouble Report -->
<div class="modal" tabindex="-1" id="view_ongoing" class="position-fixed" style="display: block; background-color: rgba(0, 0, 0, 0.5); overflow: auto;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-gradient-primary">
            <h5 class="modal-title text-white">Ongoing Trouble Report Form</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeView()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="container-fluid row overflow-auto" >
                <div class="card col mr-2">
                    <div class="row align-items-center mt-2">
                        <div class="col-auto">
                            <img src="../assets/img/logo.png" alt="">
                        </div>
                        <div class="col text-center">
                            <h6>Good</h6>
                        </div>
                    </div>
                    <br>
                    <div class="row align-items-center mb-2">
                        <div class="col-auto">
                            <img src="../assets/img/logo.png" alt="">
                        </div>
                        <div class="col text-center">
                            <h6>Not Good</h6>
                        </div>
                    </div>
                </div>

                <div class="container-fluid col">
                    <div class="card col mb-2">
                        <div class="px-2 pt-2">
                            <h6>Date: </h6>                
                            <h6>Model: </h6>
                            <h6>Department: </h6>            
                            <h6>Lot No. </h6>
                            <h6>Serial No. </h6>
                            <h6>Temp No. </h6>    
                            <h6>Quantity: </h6>   
                        </div>
                    </div>

                    <div class="card col mb-2" style="height: 100px;">
                        <div class="px-2 pt-2">
                            <h6>Findings: </h6>
                        </div>
                    </div>

                    <div class="card col mb-2"> 
                        <div class="px-2 pt-2">     
                            <h6>Findings: </h6>           
                            <h6>Trouble Origin (100%): </h6>
                            <h6>Checked By (200%): </h6>
                            <h6>Found by (QC): </h6>
                            <h6>Found by (AI): </h6>
                            <h6>Due Date: </h6>
                        </div>
                    </div>

                    <div class="card col">
                        <div class="px-2 pt-2">
                            <h6>Approval</h6>
                            <h6>Line Leader: </h6>
                            <h6>Department Head: </h6>
                            <h6>Factory Officer: </h6>
                            <h6>COO: </h6>
                        </div>
                    </div>
                </div>
            </div>         
        </div>

        <div class="modal-footer">
            <input type="reset" name="close_view" onclick="closeView()"  value="Close" class="btn btn-secondary ml-2">
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