<?php
include 'connect.php';

if (isset($_POST['login'])) :
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check in users table
  $query = "SELECT * FROM account WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
      session_start();
      $_SESSION['SESS_FULLNAME']    = $user['username'];
      $_SESSION['SESS_LEVEL']       = $user['access'];
      session_write_close();

      if($user['access'] == 1) {
        echo "Admin";
      } 
      elseif ($user['access'] == 2) {
        echo "Filer";
      } 
      elseif ($user['access'] == 3) {
        echo "Maker";
      } 
      elseif ($user['access'] == 4) {
        echo "Approver";
      } 
      elseif ($user['access'] == 5) {
        echo "Auditor";
      } 
      else {
        header('location: ../index.php');
      }
      
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "Username not found.";
  }
  mysqli_close($conn);
endif;