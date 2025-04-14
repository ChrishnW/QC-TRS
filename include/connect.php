<?php
$db_host      = '192.168.5.220';
$db_user      = 'root';
$db_database  = 'qc_trs';
$db_pass      = 'p@ssw0rd$$$';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
