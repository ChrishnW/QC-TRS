<?php include '../include/header_filer.php'; ?>

<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Request Trouble Report</h2>
                <br>                         
            </div>

            <form>
                <div class="card-body mx-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Date <span style="color: red;">*</span></label><br>
                            <input type="date" class="form-control" placeholder="mm/dd/yyyy" required>
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
                            <label for="">Temp Number <span style="color: red;">*</span></label><br>
                            <input type="text" class="form-control" placeholder="Temp Number" required>
                        </div>
                  </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Quantity <span style="color: red;">*</span></label><br>
                            <input type="number" class="form-control" placeholder="0" required min="0">
                        </div>                     
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="">Findings <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" placeholder="Problem Description" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Trouble Origin (100%) <span style="color: red;">*</span></label><br>
                            <input type="text" class="form-control" placeholder="Trouble Origin (100%)" required>
                        </div>

                        <div class="col-md-6">
                            <label for="">Checked by (200%) <span style="color: red;">*</span></label><br>
                            <input type="text" class="form-control" placeholder="Trouble Origin (200%)" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Found by (QC) <span style="color: red;">*</span></label><br>
                            <input type="text" class="form-control" placeholder="Trouble Finder (QC)" required>
                        </div>

                        <div class="col-md-6">
                            <label for="">Found by (AI) <span style="color: red;">*</span></label><br>
                            <input type="text" class="form-control" placeholder="Trouble Finder (AI)" required>
                        </div>
                    </div>                   

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Image (Good) <span style="color: red;">*</span></label><br>
                            <input type="file" class="form-control" placeholder="Image Good" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div class="col-md-6">
                            <label for="">Image (Not Good) <span style="color: red;">*</span></label><br>
                            <input type="file" class="form-control" placeholder="Image Not Good" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="">Due Date <span style="color: red;">*</span></label><br>
                            <input type="date" class="form-control" placeholder="mm/dd/yyyy" required>
                        </div>
                    </div>
                </div>                

                <div class="modal-footer">
                    <input type="reset" name="reset" value="Cancel" id="cancel_add_breaktime"  class="btn btn-secondary ml-2">
                    <input type="submit" name="" value="Submit" class="btn btn-primary pr-3">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>