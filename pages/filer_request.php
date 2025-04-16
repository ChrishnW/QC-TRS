<?php 
include '../include/header_filer.php'; 



if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['request_submit'])) {
        $date = $_POST['date'];
        $department = $_POST['department'];
        $model = $_POST['model'];
        $quantity = $_POST['quantity'];
        $lot = $_POST['lot'];
        $serial = $_POST['serial'];
        $temp = $_POST['temp'];
        $findings = $_POST['findings'];
        $origin = $_POST['origin'];
        $check = $_POST['check'];
        $found_qc = $_POST['found_qc'];
        $found_ai = $_POST['found_ai'];
        $due_date = $_POST['due_date'];
        $leader = $_POST['leader'];
        $head = $_POST['head'];
        $officer = $_POST['officer'];
        $coo = $_POST['coo'];
        $status = 0;

        if(isset($_FILES["image_good"]) && $_FILES['image_good']['error'] == 0 && isset($_FILES["image_not_good"]) && $_FILES['image_not_good']['error'] == 0) {

            $image_good_raw = $_FILES["image_good"]["name"];
            $image_good = str_replace(" ", "_", $image_good_raw);
            $image_good_path = "IMG/GOOD/" . $image_good;
            $img_temp_path_good = $_FILES["image_good"]["tmp_name"];

            move_uploaded_file($img_temp_path_good, $image_good_path);

            $image_notgood_raw = $_FILES["image_not_good"]["name"];
            $image_notgood = str_replace(" ", "_", $image_notgood_raw);
            $image_notgood_path = "IMG/NOTGOOD/" . $image_notgood;
            $img_temp_path_notgood = $_FILES["image_not_good"]["tmp_name"];

            move_uploaded_file($img_temp_path_notgood, $image_notgood_path);
            
            $result = mysqli_query($conn, "INSERT INTO tbl_request (date, model, lot, serial, temp, findings, origin1, origin2, finder_qc, finder_ai, qty, img_ng, img_g, due_date, dept_id, leader_id, dept_head_id, fac_officer_id, coo_id, status) VALUES ('$date', '$model', '$lot', '$serial', '$temp', '$findings', '$origin', '$check', '$found_qc', '$found_ai', '$quantity', '$image_notgood_path', '$image_good_path', '$due_date', '$department', '$leader', '$head', '$officer', '$coo', '$status')");

            if($result) {
                $_SESSION["message"] = "Request submitted successfully.";
            } else {
                $_SESSION["message"] = "Failed to submit request. Please try again.";
            }

        } else {
            $_SESSION["message"] = "Failed to upload images. Please try again.";
        }

        header("Refresh: .3; url=".$_SERVER['PHP_SELF']);
        ob_end_flush();
        exit;
    }
}

?>

<div class="container-fluid">
    <div id="account_dashboard" class="account_dashboard" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4 bg-primary">
                <h2 class="float-left text-white">Request Trouble Report</h2>
                <br>                         
            </div>

            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
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
                            <label for="quantity">Quantity <span style="color: red;">*</span></label><br>
                            <input type="number" name="quantity" id="quantity"  class="form-control" required min="0">
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

<!-- Pop up for Message -->
<div class="modal" tabindex="-1" id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Notification</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="close_popup">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
        if(isset($_SESSION["message"])){
          $message = $_SESSION["message"];
      
          echo "<script> 
            document.addEventListener('DOMContentLoaded', function () {
              document.getElementById('popup').style.display = 'block'; 
            }); 
          </script>";
      ?>
      
      <div class="modal-body my-2">
        <p class="h5"> <?php echo $message ?></p>
      </div>

      <?php
          unset($_SESSION["message"]);
        }
      ?>

    </div>
  </div>
</div>

<?php include '../include/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('close_popup').addEventListener('click', function () {
        document.getElementById('popup').style.display = 'none';
        });
        
    });
</script>