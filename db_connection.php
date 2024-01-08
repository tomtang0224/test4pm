<?php
$servername = "sql200.infinityfree.com";
$username = "if0_35745673";
$password = "KvbowtUDBvC";
$dbname = "if0_35745673_project_management_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);
// Create connection


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Datebase Connected successfully";
?>