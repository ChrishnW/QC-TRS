<?php include '../include/header_maker.php'; ?>

<div class="container-fluid">
    <div id="maker_form" class="maker_form" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white py-3.5 pt-4">
                <h2 class="float-left">Reason (4 M's)</h2>
                <br>                         
            </div>

            <form>
                <div class="modal-body">
                    <div class="row mb-3">                    
                        <div class="col-md-6">
                            <label for="">Man <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="">Method <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3"> 
                        <div class="col-md-6">
                            <label for="">Material <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="">Machine <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3"> 
                        <div class="col-md-12">
                            <label for="">Correction <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div>                            
                </div> 

                <div class="modal-footer">
                    <input type="submit" name="" value="Submit" class="btn btn-primary pr-3">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div id="maker_form" class="maker_form" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white py-3.5 pt-4">
                <h2 class="float-left">Corrective Action (4 M's)</h2>
                <br>                         
            </div>

            <form>
                <div class="modal-body">    
                    <div class="row mb-3">                    
                        <div class="col-md-6">
                            <label for="">Man <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="">Method <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3"> 
                        <div class="col-md-6">
                            <label for="">Material <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="">Machine <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3"> 
                        <div class="col-md-12">
                            <label for="">Remarks <span style="color: red;">*</span></label><br>
                            <textarea class="form-control" rows="5" required></textarea>
                        </div>
                    </div> 
                </div> 

                <div class="modal-footer">
                    <input type="submit" name="" value="Submit" class="btn btn-primary pr-3">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>