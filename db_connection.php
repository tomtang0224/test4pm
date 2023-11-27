<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);
// Create connection


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Datebase Connected successfully";
?>