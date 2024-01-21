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
    session_start();
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
                        $totalPrice += floatval($selectedProduct['price']);
                    }
                } else {
                    echo "No items in the cart.";
                }
                ?>

                <div class="back-to-shop"><a href="destroySession.php">&leftarrow;</a><span class="">Back to shop</span>
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
                    $totalPrice += 40;
                    echo '<div class="col text-right text-danger" id="finalPrice">&#8377; ' . number_format($totalPrice, 2) . '</div>';
                    echo '</div>';
                } else {
                    echo "Total price : 00";
                }
                ?>
                <a class="btn" href="checkoutPage.php">CHECKOUT</a>

            </div>
        </div>
    </div>




    <script>
        // Send an AJAX request to remove the item from the session
        function removeItem(key) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'removeItem.php?key=' + key, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Remove the item from the cart on the client side
                        var itemElement = document.getElementById('item-' + key);
                        itemElement.parentNode.removeChild(itemElement);

                        // Update the displayed items count on the client side
                        var itemsCountElement = document.getElementById('itemsCount');
                        var currentItemsCount = parseInt(itemsCountElement.textContent.split(' ')[1], 10);
                        itemsCountElement.textContent = 'ITEMS ' + (currentItemsCount - 1);

                        // Extract the numeric part from the total price response
                        var currentTotalPrice = parseFloat(xhr.responseText.replace(/[^0-9.]/g, ''));
                        console.log('Current Total Price:', currentTotalPrice.toFixed(2));

                        // Update the displayed total price on the client side
                        var totalPriceElement = document.getElementById('totalPrice');
                        totalPriceElement.textContent = currentTotalPrice.toFixed(2);

                        // Update the displayed final price on the client side
                        var finalPriceElement = document.getElementById('finalPrice');
                        var newFinalPrice = currentTotalPrice + 40;
                        finalPriceElement.textContent = newFinalPrice.toFixed(2);
                        console.log('New Final Price:', finalPriceElement.textContent);
                    } else {
                        // Handle error here
                        console.error('Error:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send();
        }
    </script>



   
<script>
    // Define an object to store product information
    var products = {};

    function incrementQuantity(key, price) {
        var quantityElement = document.getElementById('quantity-' + key);
        var currentQuantity = parseInt(quantityElement.innerHTML);

        // Increase quantity by 1
        var newQuantity = currentQuantity + 1;
        quantityElement.innerHTML = newQuantity;

        // Update product information
        products[key] = {
            price: price,
            quantity: newQuantity
        };

        // Recalculate and display the new total price
        var result = updateTotalPrice();
        console.log("Updated products:", result.products);
    }

    function updateTotalPrice() {
        // Calculate the new total price by summing up prices of all products
        var newTotalPrice = 0;

        for (var key in products) {
            if (products.hasOwnProperty(key)) {
                newTotalPrice += products[key].price * products[key].quantity;
            }
        }

        // Update the displayed total price on the page
        var totalPriceElement = document.getElementById('totalPriceValue');
        var finalPriceElement = document.getElementById('finalPrice');
        totalPriceElement.innerHTML = newTotalPrice.toFixed(2); // Update the total price with two decimal places

        // Return the products object along with the total price
        return {
            totalPrice: newTotalPrice,
            products: products
        };
    }

    function decrementQuantity(key, price) {
        var quantityElement = document.getElementById('quantity-' + key);
        var currentQuantity = parseInt(quantityElement.innerHTML);

        // Ensure quantity does not go below 1
        if (currentQuantity > 1) {
            // Decrease quantity by 1
            var newQuantity = currentQuantity - 1;
            quantityElement.innerHTML = newQuantity;

            // Update product information when decrementing quantity
            products[key] = {
                price: price,
                quantity: newQuantity
            };

            // Recalculate and display the new total price
            var result = updateTotalPrice();
            console.log("Updated products:", result.products);
        }
    }
</script>

   
    <script>


    </script>





</body>

</html>