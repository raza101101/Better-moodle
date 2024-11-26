<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "login";

// Create Connection
$conn=new mysqli($host, $user, $pass, $db);
if($conn->connect_error) {
    die("Failed to connect to the database: " . $conn->connect_error);
}
?>