<?php include '../include/header_maker.php'; ?>

<div class="container-fluid">
    <div id="maker_form" class="maker_form" style="display: block;">
        <div class="card shadow mb-4">
            <div class="card-header py-3.5 pt-4">
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
            <div class="card-header py-3.5 pt-4">
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