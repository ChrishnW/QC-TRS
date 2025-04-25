<?php include '../include/header_auditor.php'; ?>

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
                                $result = mysqli_query($conn, "SELECT * FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.status=0");
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                                <tr>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 22%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 18%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <button class="btn btn-primary mr-2" disabled>View</button>
                                            <button class="btn btn-success disabled" disabled>Audit</button>
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
                                $result = mysqli_query($conn, "SELECT * FROM tbl_audit INNER JOIN tbl_response ON tbl_audit.response_id=tbl_response.id INNER JOIN tbl_request ON tbl_response.request_id=tbl_request.id WHERE tbl_audit.status=0");
                                if(mysqli_num_rows($result) > 1){
                                    while($row = mysqli_fetch_assoc($result)){
                            ?>

                                <tr>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['model'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 22%;"><?php echo $row['date'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 20%;"><?php echo $row['qty'] ?? '' ?></td>
                                    <td style="table-layout: fixed; width: 18%;">
                                        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                            <button class="btn btn-primary mr-2" disabled>View</button>
                                            <button class="btn btn-success disabled" disabled>Audit</button>
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
                            <tr>
                                <td style="table-layout: fixed; width: 20%;">TR-2025-0001</td>
                                <td style="table-layout: fixed; width: 20%;">04/20/2025</td>
                                <td style="table-layout: fixed; width: 22%;">SDRB</td>
                                <td style="table-layout: fixed; width: 20%;">Closed</td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <button class="btn btn-primary mr-2">View</button>
                                        <button class="btn btn-success disabled">Audit</button>
                                    </form>
                                </td>
                            </tr>
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