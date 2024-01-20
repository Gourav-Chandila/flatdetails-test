<?php
session_start();

// Update the cartCount in the session
if (isset($_POST['cartCount'])) {
    $_SESSION['cartCount'] = $_POST['cartCount'];
}

// Update the selected products in the session
if (isset($_POST['selectedProducts'])) {
    // Decode the JSON string to an array
    $_SESSION['selectedProducts'] = json_decode($_POST['selectedProducts'], true);
}

// Check if $_SESSION['cartCount'] is set before accessing it
$cartCount = isset($_SESSION['cartCount']) ? $_SESSION['cartCount'] : 0;

// Debug statements
var_dump($cartCount);
var_dump($_SESSION['selectedProducts']);
?>