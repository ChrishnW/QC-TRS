<?php 
    include '../include/header_approver.php'; 

    function checkPendingStatus($access){
        if ($access == 4){
            return ['dept_status' => 1, 'leader_status' => 0, 'dept_head_status' => 0, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 5){
            return ['dept_status' => 1, 'leader_status' => 1, 'dept_head_status' => 0, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 6){
            return ['dept_status' => 1, 'leader_status' => 1, 'dept_head_status' => 1, 'fac_officer_status' => 0, 'coo_status' => 0];
        } elseif ($access == 7){
            return ['dept_status' => 1, 'leader_status' => 1, 'dept_head_status' => 1, 'fac_officer_status' => 1, 'coo_status' => 0];
        }
    }

    function getUsername($id){
        global $conn;
        $account = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_account WHERE id='$id'"));
        return $account['username'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        if(isset($_POST['view_pending'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];
            
            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        if (isset($_POST['view_approved'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        } 
        
        if (isset($_POST['view_rejected'])){
            $_SESSION['request_id'] = $_POST['request_id'];
            $_SESSION['response_id'] = $_POST['response_id'];

            header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
            ob_end_flush();
            exit();
        }
    
    }

?>

<!-- Pending Approvals -->
<div class="container-fluid" id="pending_reports" style="display: block;">   
    <div class="pending_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Pending Approvals</h2>
                
                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending Approval</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Approved Reports</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_rejected()">Rejected Reports</button>
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

                                $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_status={$userStatus['dept_status']} AND tbl_response.leader_status={$userStatus['leader_status']} AND tbl_response.dept_head_status={$userStatus['dept_head_status']} AND tbl_response.factory_status={$userStatus['fac_officer_status']} AND tbl_response.coo_status={$userStatus['coo_status']} AND (tbl_request.dept_id=$userId OR tbl_request.leader_id=$userId OR tbl_request.dept_head_id=$userId OR tbl_request.fac_officer_id=$userId OR tbl_request.coo_id=$userId)");
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_pending" value="View" class="btn btn-primary" disabled>
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
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Approved Reports</h2>
               
                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Approval</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary active" onclick="display_approved()">Approved Reports</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_rejected()">Rejected Reports</button>
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
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.leader_status=$approvedStatus AND tbl_request.leader_id=$userId");
                                } elseif ($userAccess == 5){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_head_status=$approvedStatus AND tbl_request.dept_head_id=$userId");
                                } elseif ($userAccess == 6){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.factory_status=$approvedStatus AND tbl_request.fac_officer_id=$userId");
                                } elseif ($userAccess == 7){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.coo_status=$approvedStatus AND tbl_request.coo_id=$userId");
                                }
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){

                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_approved" value="View" class="btn btn-primary" disabled>
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
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Rejected Reports</h2>

                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary" onclick="display_pending()">Pending Approval</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Approved Reports</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary active" onclick="display_rejected()">Rejected Reports</button>
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
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.leader_status=$rejectedStatus AND tbl_request.leader_id=$userId");
                                } elseif ($userAccess == 5){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.dept_head_status=$rejectedStatus AND tbl_request.dept_head_id=$userId");
                                } elseif ($userAccess == 6){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.factory_status=$rejectedStatus AND tbl_request.fac_officer_id=$userId");
                                } elseif ($userAccess == 7){
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_request INNER JOIN tbl_response on tbl_request.id=tbl_response.request_id WHERE tbl_response.coo_status=$rejectedStatus AND tbl_request.coo_id=$userId");
                                }
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){

                            ?>

                            <tr>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 22%;"><?php echo $row['dept_id'] ? getUsername($row['dept_id']) : '' ?></td>
                                <td class="text-left align-middle" style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id'] ?>">
                                        <input type="hidden" name="response_id" value="<?php echo $row['id'] ?>">
                                        <input type="submit" name="view_rejected" value="View" class="btn btn-primary" disabled>
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
</script>