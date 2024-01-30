<?php
session_start(); // Start the session
require '../phpfiles/insertData.php';
require '../phpfiles/generateUniqueId.php';
require '../phpfiles/db_connect.php';

// Check if the session variable for cart data exists
if (!isset($_SESSION['cart_data'])) {
    echo 'Error: Cart data not found. Please try again later or contact to support team.';
    exit(); // Stop further execution
}

// Retrieve the JSON data from the session variable
$cartDataJson = $_SESSION['cart_data'];
// Decode the JSON data
$cartData = json_decode($cartDataJson, true);
// Convert the PHP array to JSON format
// $jsonData = json_encode($cartData, JSON_PRETTY_PRINT);
// echo "$jsonData";

// Check if the JSON data is valid
if ($cartData === null) {
    // Handle the case where JSON decoding failed
    echo 'Error: Unable to process data. Please try again later or contact to support team.';
    error_log("Error decoding JSON data in place order file", 0); //shows in log file
    exit(); // Stop further execution
}

// Check if the order has already been placed in the current session
if (isset($_SESSION['order_placed']) && $_SESSION['order_placed'] === true) {
    echo 'Order already placed';
    exit(); // Stop further execution
}


//Accessing json data 
$standardShippingCharge = $cartData['Order_adjustment']['AMOUNT'];
$subTotal = $cartData['Total']['sub_total'];
$grandTotal = $cartData['Total']['grand_total'];

//Accessing Billing_address object data 
$billingAddress1 = $cartData['Billing_address']['Address1'];
$billingAddress2 = $cartData['Billing_address']['Address2'];
$billingAddflatUnitNumber = $cartData['Billing_address']['Flat_unit_number'];

//Accessing Shipping_address object data
$shippingAddress1 = $cartData['Shipping_address']['Address1'];
$shippingAddress2 = $cartData['Shipping_address']['Address2'];
$shippingAddflatUnitNumber = $cartData['Shipping_address']['Flat_unit_number'];

// Generate unique order ID
$orderId = generateUniqueId($conn, "SH_CT", 'order_header');
$userLoginId = $_SESSION['user_login_id'];

if ($orderId !== false) {
    $userLoginId = $_SESSION['user_login_id'];

    // Insert order header data
    $insertOrderHeader = array(
        'ORDER_ID' => $orderId,
        'ORDER_TYPE_ID' => 'SALES_ORDER',
        'REMAINING_SUB_TOTAL' => $subTotal,
        'GRAND_TOTAL' => $grandTotal,
        'STATUS_ID' => 'ORDER_CREATED',
        'CREATED_BY' => $userLoginId,
        'CURRENCY_UOM' => 'INR',
    );
    insertData("order_header", $insertOrderHeader, $conn);

    // Insert order contact mechanism data
    $contactMechId = $cartData['Billing_address']['CONTACT_MECH_ID'];
    $contactMechPurpTypeId = $cartData['Billing_address']['CONTACT_MECH_PURPOSE_TYPE_ID'];
    // echo "contact is".$contactMechPurpTypeId;
    $insertOrderContactMech = array(
        'ORDER_ID' => $orderId,
        'CONTACT_MECH_PURPOSE_TYPE_ID' => $contactMechPurpTypeId,
        'CONTACT_MECH_ID' => $contactMechId,
    );
    insertData("order_contact_mech", $insertOrderContactMech, $conn);
    // Initialize $sequence variable
    $sequence = 0;
    if (isset($cartData['Products'])) {
        // Access the 'Products' array
        $productsArray = $cartData['Products'];
        // Iterate over each product
        foreach ($productsArray as $product) {
            // Count the number of products in the array
            $numProducts = count($productsArray);


            // Increment the sequence
            $sequence++;
            // Format the order item sequence ID with leading zeros
            $order_item_seq_id = sprintf("%05d", $sequence);
            // Extract desired information
            $productId = $product['product_id'];
            $pricePerItem = $product['price_per_item'];
            $quantity = $product['quantity'];

            // Perform actions with the extracted information
            $insertOrderItem = array(
                'ORDER_ID' => $orderId,
                'ORDER_ITEM_SEQ_ID' => $order_item_seq_id,
                'ORDER_ITEM_TYPE_ID' => 'PRODUCT_ORDER_ITEM',
                'PRODUCT_ID' => $productId,
                'PROD_CATALOG_ID' => 'ShoesCatalog',
                'IS_PROMO' => 'N',
                'QUANTITY' => $quantity,
                'UNIT_PRICE' => $pricePerItem,
                'STATUS_ID' => 'ITEM_CREATED',
                'CHANGE_BY_USER_LOGIN_ID' => $userLoginId,
            );
            insertData("order_item", $insertOrderItem, $conn);


            $orderAdjustmentId = generateUniqueId($conn, "ODR_ADJ", 'order_adjustment');
            if ($orderId !== false) {
                $orderAdjustmentTypeId = $cartData['Order_adjustment']['ORDER_ADJUSTMENT_TYPE_ID'];
                $orderAdjustmentDesc = $cartData['Order_adjustment']['DESCRIPTION'];
                $orderAdjustmentAmount = $cartData['Order_adjustment']['AMOUNT'];
                $insertOrderAdjustment = array(
                    'ORDER_ADJUSTMENT_ID' => $orderAdjustmentId,
                    'ORDER_ADJUSTMENT_TYPE_ID' => $orderAdjustmentTypeId,
                    'ORDER_ID' => $orderId,
                    'ORDER_ITEM_SEQ_ID' => $order_item_seq_id,
                    'DESCRIPTION' => $orderAdjustmentDesc,
                    'AMOUNT' => $orderAdjustmentAmount,
                );
                insertData("order_adjustment", $insertOrderAdjustment, $conn);
            }
        }
    }
    // Set session variable to indicate order has been placed successfully
    $_SESSION['order_placed'] = true;
    echo "Order placed successfully!";
} else {
    echo "Error: Order not created. Some error occurred.";
}
?>