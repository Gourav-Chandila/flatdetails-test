<?php

// Database connection details
$servername = "148.66.138.138";
$username = "v6";
$db_password = "s[jT64YJ=3RT";
$database = "v6"; // Database name

// Create a connection to the database
$conn = mysqli_connect($servername, $username, $db_password, $database);
// Check if the connection was successful
if (!$conn) {
    die("Sorry we failed to connect: ");
  
}

// // Database connection details
// $servername = "localhost";
// $username = "root";
// $db_password = "";
// $database = "v6"; // Database name

// // Create a connection to the database
// $conn = mysqli_connect($servername, $username, $db_password, $database);
// // Check if the connection was successful
// if (!$conn) {
//     die("Sorry we failed to connect: ");
// }
?>