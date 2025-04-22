<?php include '../include/header_auditor.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending Audits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">40</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-clock fa-2x text-gray-300"></i> <!-- Changed to clock icon -->
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
                                Approved Audits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">10</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-check-circle fa-2x text-gray-300"></i> <!-- Changed to check-circle -->
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
                                Rejected Audits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-times-circle fa-2x text-gray-300"></i> <!-- Changed to times-circle -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pending_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Audit Reports</h2>
                
                <div class="btn-group float-right pb-2">
                    <div class="btn-group" role="group" aria-label="Switch Buttons">
                        <button id="display_pending" type="button" class="btn btn-outline-primary active" onclick="display_pending()">Pending Audit</button>
                        <button id="display_approved" type="button" class="btn btn-outline-primary" onclick="display_approved()">Audited</button>
                        <button id="display_rejected" type="button" class="btn btn-outline-primary" onclick="display_rejected()">Closed</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="pending_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Report ID</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TR-2025-0001</td>
                                <td>04/20/2025</td>
                                <td>SDRB</td>
                                <td>Pending</td>
                                <td>
                                    <button class="btn btn-primary">View</button>
                                    <button class="btn btn-success">Audit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TR-2025-0001</td>
                                <td>04/20/2025</td>
                                <td>SDRB</td>
                                <td>Pending</td>
                                <td>
                                    <button class="btn btn-primary">View</button>
                                    <button class="btn btn-success">Audit</button>
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