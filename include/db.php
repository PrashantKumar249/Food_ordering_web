<?php
$host = ''; // Database host
$user = ''; // Database username
$pass = ''; // Database password
$dbname = ''; // Database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// session_start(); 
?>
