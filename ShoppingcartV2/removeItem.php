<?php
session_start();

if (isset($_GET['key'])) {
    $key = $_GET['key'];

    // Remove the item from the session based on the key
    if (isset($_SESSION['selectedProducts'][$key])) {
        unset($_SESSION['selectedProducts'][$key]);
    }

    // Recalculate total price
    $totalPrice = 0;
    foreach ($_SESSION['selectedProducts'] as $product) {
        $totalPrice += floatval($product['price']);
    }

    // Return the updated total price as a response
    echo number_format($totalPrice, 2);
}
?>

