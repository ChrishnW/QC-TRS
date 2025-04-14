<?php include '../include/header_filer.php'; ?>

<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <div class="col-md-12">
                    <h5 class="float-left">Request Trouble Report</h1>
                    <br>
                </div>                           
            </div>

            <div class="modal-body">
                <form>
                    <div>
                        <div class="card mb-4">
                            <div class="card-body">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Date <span style="color: red;">*</span></label><br>
                                        <input type="date" class="form-control" placeholder="00/00/00" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Department <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Department Name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Model <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Machine Name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Lot Number <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" placeholder="Lot Number" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Serial Number <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Serial Number" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Temp # <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Temp #" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Quantity <span style="color: red;">*</span></label><br>
                                        <input type="number" class="form-control" placeholder="Serial Number" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Due Date <span style="color: red;">*</span></label><br>
                                        <input type="date" class="form-control" placeholder="Temp #" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Image (Good) <span style="color: red;">*</span></label><br>
                                        <input type="file" class="form-control" placeholder="Serial Number" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Image (Not Good) <span style="color: red;">*</span></label><br>
                                        <input type="file" class="form-control" placeholder="Temp #" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Trouble Origin (100%) <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Trouble Origin" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Trouble Origin (200%) <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Trouble Origin" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Trouble Finder (QC) <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Trouble Finder" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Trouble Finder (AI) <span style="color: red;">*</span></label><br>
                                        <input type="text" class="form-control" placeholder="Trouble Finder" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="">Findings <span style="color: red;">*</span></label><br>
                                        <textarea class="form-control" rows="5" placeholder="Problem Description" required></textarea>
                                    </div>

                                    <div class="col-md-6">
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>