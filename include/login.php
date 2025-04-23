<?php
include 'connect.php';
session_start();

if (isset($_POST['username'])) :
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

  // Check in users table
  $query = "SELECT * FROM tbl_account WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
 
      $_SESSION['SESS_FULLNAME']    = $user['username'];
      $_SESSION['SESS_LEVEL']       = $user['access'];
      $_SESSION['SESS_USERID']      = $user['id'];

      if($user['access'] == 1) {
        echo "Admin";
      } 
      elseif ($user['access'] == 2) {
        echo "Filer";
      } 
      elseif ($user['access'] == 3) {
        echo "Maker";
      } 
      elseif ($user['access'] >= 4 && $user['access'] <= 7) {
        echo "Approver";
      } 
      elseif ($user['access'] == 8) {
        echo "Auditor";
      } 
      else {
        echo "Incorrect password.";
      }
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "Username not found.";
  }
  mysqli_close($conn);
endif;