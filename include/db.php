<?php
$host = '';
$user = '';
$pass = '';
$dbname = '';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// session_start(); 
?>
