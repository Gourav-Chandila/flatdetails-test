<?php
session_start();
session_unset(); //frees all session variables
session_destroy();
header("location: https://localhost/sysnomy/flatdetails-test/Shoppingcart/cartPage.php"); 
exit;
?>
