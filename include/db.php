<?php
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = ''; // Database password
$dbname = 'khana_khazana'; // Database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// session_start(); 
?>
