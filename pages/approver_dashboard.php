<?php include '../include/header_approver.php'; ?>

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
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Approval Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="table-layout: fixed; width: 20%;">REQ12346</td>
                                <td style="table-layout: fixed; width: 20%;">2023-10-02</td>
                                <td style="table-layout: fixed; width: 22%;">Finance</td>
                                <td style="table-layout: fixed; width: 20%;">Level 2</td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <button class="btn btn-success mr-2">Approve</button>
                                        <button class="btn btn-danger">Reject</button>
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
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Approval Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="table-layout: fixed; width: 20%;">REQ12346</td>
                                <td style="table-layout: fixed; width: 20%;">2023-10-02</td>
                                <td style="table-layout: fixed; width: 22%;">Finance</td>
                                <td style="table-layout: fixed; width: 20%;">Level 2</td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <button class="btn btn-success mr-2">Approve</button>
                                        <button class="btn btn-danger">Reject</button>
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
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Approval Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="table-layout: fixed; width: 20%;">REQ12346</td>
                                <td style="table-layout: fixed; width: 20%;">2023-10-02</td>
                                <td style="table-layout: fixed; width: 22%;">Finance</td>
                                <td style="table-layout: fixed; width: 20%;">Level 2</td>
                                <td style="table-layout: fixed; width: 18%;">
                                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form_table d-flex justify-content-center align-items-center">
                                        <button class="btn btn-success mr-2">Approve</button>
                                        <button class="btn btn-danger">Reject</button>
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