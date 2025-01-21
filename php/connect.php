<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";

// Create Connection
$conn=new mysqli($host, $user, $pass, $dbname);
if($conn->connect_error) {
    die("Failed to connect to the database: " . $conn->connect_error);
}
?>