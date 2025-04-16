<?php include '../include/header_approver.php'; ?>

<div class="container-fluid">   
    <div class="filer_dashboard">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Ongoing Trouble Report Request</h2>

                <div class="btn-group float-right" role="group" aria-label="Switch Buttons">
                    <button id="pendingApproval" type="button" class="btn btn-outline-primary active" onclick="display_ongoing()">Ongoing</button>
                    <button id="approved" type="button" class="btn btn-outline-primary" onclick="display_finished()">Finished</button>
                    <button id="finishedBtn" type="button" class="btn btn-outline-primary" onclick="display_finished()">Finished</button>
                </div>
            </div>

</div>

<?php include '../include/footer.php'; ?>