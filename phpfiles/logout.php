<?php
session_start();
session_unset(); //frees all session variables
session_destroy();

header("location: /sysnomy/flatdetails-test/index.php"); //when we click on logout it redirect to login page
exit;
?>