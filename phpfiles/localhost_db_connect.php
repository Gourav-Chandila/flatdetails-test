<?php

// Database connection details
$servername = "localhost";
$username = "root";
$db_password = "";
$database = "v6"; // Database name

// Create a connection to the database
$conn = mysqli_connect($servername, $username, $db_password, $database);
// Check if the connection was successful
if (!$conn) {
    die("Sorry we failed to connect: ");
}
?>