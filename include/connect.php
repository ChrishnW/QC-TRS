<?php
$db_host      = 'localhost';
$db_user      = 'root';
$db_database  = 'test';
$db_pass      = 'test';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
