<?php
require_once("config.php");

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS,DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
