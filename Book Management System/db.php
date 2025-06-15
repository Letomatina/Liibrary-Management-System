<?php

// Database connection settings
$server = "localhost";
$user = "root";
$pass = "";
$dbname = "librarydb";

// Try to connect
$conn = mysqli_connect($server, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 