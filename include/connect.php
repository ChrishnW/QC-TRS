<?php
$db_host      = '192.168.5.220';
$db_user      = 'root';
$db_database  = 'qc_trs';
$db_pass      = 'p@55w0rd$$$';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

