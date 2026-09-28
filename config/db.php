<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "campuscare";
$port = 3307;

// Connect to MySQL
$conn = mysqli_connect($host, $user, $password, $database, $port);

// Check connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set UTF-8
mysqli_set_charset($conn, "utf8mb4");

?>