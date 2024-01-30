<?php
session_start();
session_unset(); //frees all session variables
session_destroy();

// Determine the base URL dynamically
$baseURL = "//" . $_SERVER['HTTP_HOST'] . "/sysnomy/flatdetails-test/ShoppingcartV2/";
$page = "home_page.php";

// Construct the dynamic URL
$redirectURL = $baseURL . $page;

// Redirect to the dynamic URL
header("Location: $redirectURL");
exit; // Ensure that subsequent code is not executed after redirection

?>