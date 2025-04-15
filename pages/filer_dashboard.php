<?php include '../include/header_filer.php'; ?>

<div class="container-fluid" id="ongoing_trouble_report" style="display: block;">
    <div class="filer_dashboard">
        <div class="card shadow mb-4">

            <div class="cold-md-6 float-left m-3">
                <a href="#" class="btn btn-primary active" onclick="display_ongoing()" role="button" aria-pressed="true">Ongoing Trouble Report Request</a>
                <a href="#" class="btn btn-primary active" onclick="display_finished()" role="button" aria-pressed="true">Finished Trouble Report Request</a>
            </div> 

            <div class="card-header py-3.5 pt-1">
                <h2 class="float-left">Ongoing Trouble Report</h2>            
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped" id="ongoing_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>---</th>
                                <th>---</th>
                                <th>---</th>
                                <th style="width: 170px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tbody>
                    </table>
                </div>        
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="finished_trouble_report" style="display: none;">
    <div class="filer_dashboard">
        <div class="card shadow mb-4">

            <div class="cold-md-6 float-left m-3">
                <a href="#" class="btn btn-primary active" onclick="display_ongoing()" role="button" aria-pressed="true">Ongoing Trouble Report Request</a>
                <a href="#" class="btn btn-primary active" onclick="display_finished()" role="button" aria-pressed="true">Finished Trouble Report Request</a>
            </div> 

            <div class="card-header py-3.5 pt-1">
                <h2 class="float-left">Finished Trouble Report</h2>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped" id="finished_dataTable" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>---</th>
                                <th>---</th>
                                <th>---</th>
                                <th style="width: 170px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
    };

    function display_finished() {
        document.getElementById("ongoing_trouble_report").style.display = "none";
        document.getElementById("finished_trouble_report").style.display = "block";
    };




</script>