<?php include '../include/header_filer.php'; ?>

<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
                <h2 class="float-left">Request Trouble Report</h2>
                <br>                         
            </div>

            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="card-body mx-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date">Date <span style="color: red;">*</span></label><br>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="department">Department <span style="color: red;">*</span></label><br>
                            <select name="department"  id="department" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=3 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="model">Model <span style="color: red;">*</span></label><br>
                            <input type="text" name="model" id="model" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="quality">Quantity <span style="color: red;">*</span></label><br>
                            <input type="number" name="quality" id="quality"  class="form-control" required min="0">
                        </div>   
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-4">
                            <label for="lot">Lot Number <span style="color: red;">*</span></label>
                            <input type="text" name="lot" id="lot" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="serial">Serial Number <span style="color: red;">*</span></label><br>
                            <input type="text" name="serial" id="serial" class="form-control" required>
                        </div>  

                        <div class="col-md-4">
                            <label for="temp">Temp Number <span style="color: red;">*</span></label><br>
                            <input type="text" name="temp" id="temp" class="form-control" required>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="findings">Findings <span style="color: red;">*</span></label><br>
                            <textarea name="findings" id="findings" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="origin">Trouble Origin (100%) <span style="color: red;">*</span></label><br>
                            <input type="text" name="origin" id="origin" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="check">Checked by (200%) <span style="color: red;">*</span></label><br>
                            <input type="text" name="check" id="check" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="found_qc">Found by (QC) <span style="color: red;">*</span></label><br>
                            <input type="text" name="found_qc" id="found_qc" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="found_ai">Found by (AI) <span style="color: red;">*</span></label><br>
                            <input type="text" name="found_ai" id="found_ai" class="form-control" required>
                        </div>
                    </div>                   

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="image_good">Image (Good) <span style="color: red;">*</span></label><br>
                            <input type="file" name="image_good" id="image_good" class="form-control" style="height: auto;" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>

                        <div class="col-md-6">
                            <label for="image_not_good">Image (Not Good) <span style="color: red;">*</span></label><br>
                            <input type="file" name="image_not_good" id="image_not_good" class="form-control" style="height: auto;" required accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="due_date">Due Date <span style="color: red;">*</span></label><br>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <h5>Approval</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="leader">Leader <span style="color: red;">*</span></label><br>
                            <select name="leader" id="leader" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=4 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>
                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="head">Department Head <span style="color: red;">*</span></label><br>
                            <select name="head" id="head" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=5 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="officer">Factory Officer <span style="color: red;">*</span></label><br>
                            <select name="officer" id="officer" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=6 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="coo">Chief Operating Office (COO) <span style="color: red;">*</span></label><br>
                            <select name="coo" id="coo" class="form-control" required >
                                <option value="" hidden></option>

                                <?php 
                                    $result = mysqli_query($conn, "SELECT * FROM tbl_account WHERE access=7 AND status=1");
                                    if($result) {    
                                        while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>

                                <?php 
                                        }
                                    }
                                ?>

                            </select>
                        </div>
                    </div>


                <div class="modal-footer">
                    <input type="submit" name="request_submit" value="Submit" class="btn btn-primary pr-3">
                    <input type="reset" value="Cancel" class="btn btn-secondary ml-2">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>