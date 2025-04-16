<?php include '../include/header_filer.php'; ?>

<!-- Ongoing Trouble Report -->
<div class="container-fluid" id="ongoing_trouble_report" style="display: block;">
    <div class="filer_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Ongoing Trouble Report Request</h2>

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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

<!-- Finished Trouble Report -->
<div class="container-fluid" id="finished_trouble_report" style="display: none;">
    <div class="filer_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Finished Trouble Report Request</h2>

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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
</script>