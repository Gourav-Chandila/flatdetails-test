<?php
session_start();
// Get the key and new quantity from the AJAX request
$key = isset($_GET['key']) ? $_GET['key'] : null;
$newQuantity = isset($_GET['quantity']) ? $_GET['quantity'] : null;

if ($key !== null && $newQuantity !== null) {
    // Update the quantity in the session
    if (isset($_SESSION['selectedProducts'][$key])) {
        $_SESSION['selectedProducts'][$key]['quantity'] = $newQuantity;
        echo 'Quantity updated successfully.';
    } else {
        echo 'Error updating quantity: Product not found in the session.';
    }
} else {
    echo 'Error updating quantity: Invalid parameters.';
}
?>
