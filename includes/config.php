<?php

// Database Configuration

$dbHost = "localhost";
$dbUser = "root";
$dbPW = "";
$dbName = "simplefaucet";

// Establish connection

$mysqli = mysqli_connect($dbHost, $dbUser, $dbPW, $dbName);

// Check connection
if(mysqli_connect_errno()){
 	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Website

$Website_Url = "https://127.0.0.1:443";

?>