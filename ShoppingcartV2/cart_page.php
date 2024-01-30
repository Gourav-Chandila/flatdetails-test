<?php
require '../phpfiles/navbar.php';

function createCartJSON()
{
    // Initialize an empty array to store cart data
    $cartData = [];

    // Check if there are items in the cart
    if (!empty($_SESSION['selectedProducts'])) {
        // Iterate through each item in the cart
        foreach ($_SESSION['selectedProducts'] as $product) {
            // Create an associative array for each product
            $productData = [
                "product_id" => $product['product_id_to'],
                "product_name" => htmlspecialchars($product['productName']),
                "quantity" => (int) $product['quantity'],
                "price_per_item" => $product['price'],
                "size" => htmlspecialchars($product['size'])
            ];

            // Add the product data to the "Products" array
            $cartData["Products"][] = $productData;
        }
    }

    // Add order adjustment object directly to cartData
    $orderAdjustmentObject = [
        "ORDER_ADJUSTMENT_TYPE_ID" => "STANDARD_DELIVERY",
        "DESCRIPTION" => "40 rupees standard delivery charge",
        "AMOUNT" => 40
    ];
    $cartData["Order_adjustment"] = $orderAdjustmentObject;

    // Add shipping address object directly to cartData
    $shippingAddressObject = [
        "CONTACT_MECH_ID" => $_SESSION['contact_mech_id'] ?? null,
        "CONTACT_MECH_PURPOSE_TYPE_ID" => $_SESSION['contact_mech_purpose_type_id'] ?? null,
        "Address1" => $_SESSION['address1'] ?? null,
        "Address2" => $_SESSION['address2'] ?? null,
        "Flat_unit_number" => $_SESSION['flat_unit_number'] ?? null
    ];
    $cartData["Shipping_address"] = $shippingAddressObject;

    // Add billing address object directly to cartData
    $billingAddressObject = [
        "CONTACT_MECH_ID" => $_SESSION['contact_mech_id'] ?? null,
        "CONTACT_MECH_PURPOSE_TYPE_ID" => $_SESSION['contact_mech_purpose_type_id'] ?? null,
        "Address1" => $_SESSION['address1'] ?? null,
        "Address2" => $_SESSION['address2'] ?? null,
        "Flat_unit_number" => $_SESSION['flat_unit_number'] ?? null
    ];
    $cartData["Billing_address"] = $billingAddressObject;

    // Add subtotal directly to cartData
    $subTotal = calculateSubTotal($_SESSION['selectedProducts']);
    $totalPriceObject = [
        "sub_total" => $subTotal,
        "grand_total" => $subTotal + 40 // Assuming 40 is the standard delivery charge
    ];
    $cartData["Total"] = $totalPriceObject;

    // Convert the PHP array to JSON
    $jsonCartData = json_encode($cartData, JSON_PRETTY_PRINT);

    // Return the final JSON representation of cartData
    return $jsonCartData;
}

// Function to calculate sub_total based on quantity and store in cart_data session to show json in checkout
function calculateSubTotal($selectedProducts)
{
    $subTotal = 0;
    foreach ($selectedProducts as $product) {
        $subTotal += $product['price'] * $product['quantity'];
    }
    return $subTotal;
}
// Call the function and store the result in a session variable
$_SESSION['cart_data'] = createCartJSON();

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>

<body>

    <?php
    // Ensure $_SESSION['selectedProducts'] is set and is an array
    if (!isset($_SESSION['selectedProducts']) || !is_array($_SESSION['selectedProducts'])) {
        $_SESSION['selectedProducts'] = [];
    }

    $cartCount = count($_SESSION['selectedProducts']);
    $totalPrice = 0;
    ?>

    <div class="card">
        <div class="row">
            <div class="col-md-8 cart">
                <div class="title">
                    <div class="row">
                        <div class="col">
                            <h4><b>Cart</b></h4>
                        </div>

                    </div>
                </div>

                <?php
                if ($cartCount > 0) {
                    foreach ($_SESSION['selectedProducts'] as $key => $selectedProduct) {
                        // Add a unique identifier (e.g., product ID) to each item
                        echo '<div id="item-' . $key . '" class="row border-top border-bottom">';
                        echo '<div class="row main align-items-center">';
                        echo '<div class="col-2"><img class="img-fluid" src="img/session_imgs/' . htmlspecialchars($selectedProduct['productImage']) . '"></div>';
                        echo '<div class="col">';
                        echo '<div class="row text-muted">' . htmlspecialchars($selectedProduct['productName']) . '</div>';
                        echo '<div class="row">Size : ' . htmlspecialchars($selectedProduct['size']) . '</div>';
                        echo '</div>';

                        // Quantity controls
                        echo '<div class="quantity-controls">';
                        echo '<a href="#" onclick="decrementQuantity(' . $key . ', ' . $selectedProduct['price'] . '); event.preventDefault();">-</a>';
                        echo '<span id="quantity-' . $key . '">' . $selectedProduct['quantity'] . '</span>';
                        echo '<a href="#" onclick="incrementQuantity(' . $key . ', ' . $selectedProduct['price'] . '); event.preventDefault();">+</a>';
                        echo '</div>';

                        echo '<div class="col"></div>';
                        echo '<div class="col ">&#8377; ' . htmlspecialchars($selectedProduct['price']) . ' <span class="close" onclick="removeItem(' . $key . ')">&#10005;</span></div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No items in the cart.";
                }
                ?>

                <div class="back-to-shop"><a href="destroy_session.php">&leftarrow;</a><span class="">Back to shop</span>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="col-md-4 summary">
                <div>
                    <h5><b>Summary</b></h5>
                </div>
                <hr>

                <?php
                if ($cartCount > 0) {
                    echo '<div class="row">';
                    echo '<div class="col" style="padding-left:0;" id="itemsCount">ITEMS ' . $cartCount . '</div>';
                    echo '<div class="col text-right text-danger" id="totalPrice">&#8377;Total item price: <span id="totalPriceValue">' . number_format($totalPrice, 2) . '</span></div>';
                    echo '</div>';
                } else {
                    echo " No items in the cart.";
                }
                ?>

                <form>
                    <p>SHIPPING</p>
                    <select>
                        <option class="text-muted">Standard-Delivery- &#8377;40</option>
                    </select>
                    <p>GIVE CODE</p>
                    <input id="code" placeholder="Enter your code">
                </form>

                <?php
                if ($cartCount > 0) {
                    echo '<div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">';
                    echo '<div class="col">TOTAL PRICE</div>';

                    if ($totalPrice === 0) {
                        $totalPrice += 00;
                    } else {
                        $totalPrice += 40;
                    }
                    echo '<div class="col text-right text-danger" id="finalPrice">&#8377; ' . number_format($totalPrice, 2) . '</div>';
                    echo '</div>';
                }
                ?>
                <a class="btn" href="checkout_page.php">CHECKOUT</a>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            updateTotalPriceOnReload();
        };
        function removeItem(key) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'remove_item.php?key=' + key, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var itemElement = document.getElementById('item-' + key);
                        itemElement.parentNode.removeChild(itemElement);
                        var itemsCountElement = document.getElementById('itemsCount');
                        var currentItemsCount = parseInt(itemsCountElement.textContent.split(' ')[1], 10);
                        itemsCountElement.textContent = 'ITEMS ' + (currentItemsCount - 1);
                        updateTotalPrice();
                    } else {
                        console.error('Error:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        function incrementQuantity(key, price) {
            var quantityElement = document.getElementById('quantity-' + key);
            var currentQuantity = parseInt(quantityElement.textContent);
            var newQuantity = currentQuantity + 1;
            quantityElement.textContent = newQuantity;

            updatePrices(key, price, newQuantity);
            updateQuantityOnServer(key, newQuantity);
        }

        function decrementQuantity(key, price) {
            var quantityElement = document.getElementById('quantity-' + key);
            var currentQuantity = parseInt(quantityElement.textContent);
            if (currentQuantity > 1) {
                var newQuantity = currentQuantity - 1;
                quantityElement.textContent = newQuantity;

                updatePrices(key, price, newQuantity);
                updateQuantityOnServer(key, newQuantity);
            }
        }

        function updateQuantityOnServer(key, newQuantity) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'update_quantity.php?key=' + key + '&quantity=' + newQuantity, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status != 200) {
                        console.error('Error updating quantity:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        }

        function updatePrices(key, itemPrice, quantity) {
            var newItemTotalPrice = itemPrice * quantity;
            var itemTotalPriceElement = document.getElementById('item-' + key).querySelector('.col:last-child');
            itemTotalPriceElement.innerHTML = '&#8377; ' + newItemTotalPrice.toFixed(2);
            updateTotalPrice();
        }

        function updateTotalPrice() {
            var totalPrice = 0;
            var itemsCount = 0;

            document.querySelectorAll('.row.main').forEach(function (item) {
                var quantity = parseInt(item.querySelector('.quantity-controls span').textContent);
                var price = parseFloat(item.querySelector('.col:last-child').textContent.replace(/[^0-9.]/g, ''));

                totalPrice += price;
                itemsCount += quantity;
            });

            var itemsCountElement = document.getElementById('itemsCount');
            itemsCountElement.textContent = 'ITEMS ' + itemsCount;

            var totalPriceElement = document.getElementById('totalPrice');
            totalPriceElement.innerHTML = '&#8377;Total item price: <span id="totalPriceValue">' + totalPrice.toFixed(2) + '</span>';

            var finalPriceElement = document.getElementById('finalPrice');

            if (totalPrice === 0) {
                var newFinalPrice = 0; // Assigning 0 to newFinalPrice when totalPrice is 0
                finalPriceElement.innerHTML = '&#8377; ' + newFinalPrice.toFixed(2);
            } else {
                var newFinalPrice = totalPrice + 40; // Adding 40 to totalPrice when it's not 0
                finalPriceElement.innerHTML = '&#8377; ' + newFinalPrice.toFixed(2);
            }

        }

        // Update total price of all items remove this because its duplicate code and not good practice
        function updateTotalPriceOnReload() {
            var totalPrice = 0;
            var itemsCount = 0;
            document.querySelectorAll('.row.main').forEach(function (item) {
                var quantity = parseInt(item.querySelector('.quantity-controls span').textContent);
                var price = parseFloat(item.querySelector('.col:last-child').textContent.replace(/[^0-9.]/g, ''));
                totalPrice += price * quantity;
                itemsCount += quantity;
            });
            var itemsCountElement = document.getElementById('itemsCount');
            itemsCountElement.textContent = 'ITEMS ' + itemsCount;
            var totalPriceElement = document.getElementById('totalPrice');
            totalPriceElement.innerHTML = '&#8377;Total item price: <span id="totalPriceValue">' + totalPrice.toFixed(2) + '</span>';
            var finalPriceElement = document.getElementById('finalPrice');
            finalPriceElement.innerHTML = '&#8377; ' + (totalPrice === 0 ? 0 : totalPrice + 40).toFixed(2);
        }
    </script>


</body>

</html>