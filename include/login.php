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
      $_SESSION['SESS_USERNAME']    = $username;
      $_SESSION['SESS_PASSWORD']    = $password;
      $_SESSION['SESS_FULLNAME']    = $user['fullname'];
      $_SESSION['SESS_LEVEL']       = $user['level'];
      session_write_close();
      echo "Success";
    } else {
      echo "Incorrect password.";
    }
  } else {
    echo "Username not found.";
  }
  mysqli_close($conn);
endif;