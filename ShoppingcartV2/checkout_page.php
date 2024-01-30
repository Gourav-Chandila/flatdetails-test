<?php
require '../phpfiles/navbar.php'; //including navbar
require '../phpfiles/insertData.php';
require '../phpfiles/generateUniqueId.php';
require '../phpfiles/db_connect.php';

// Retrieve the JSON data from the session variable
$cartDataJson = $_SESSION['cart_data'] ?? null;
// Decode the JSON data
$cartData = json_decode($cartDataJson, true);

// Check if the JSON data is valid
if ($cartData === null) {
    // Handle the case where JSON decoding failed
    echo 'Error decoding JSON data.';
} else {
    // Convert the PHP array to JSON format
    $jsonData = json_encode($cartData, JSON_PRETTY_PRINT);
    // Output the JSON data
    // echo $jsonData;

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
}
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>

    <div class="container mt-5">
        <!-- <div id="alertContainer"></div> -->
        <div class="row">
            <div class="col-md-8 mt-5">
                <h4>Checkout</h4>
                <form>
                    <!-- Billing Address -->
                    <div class="form-group">
                        <label for="billingAddress">Billing Address</label>
                        <textarea class="form-control" id="billingAddress" rows="3"><?php
                        echo "Address1: " . $billingAddress1 . "\n";
                        echo "Address2: " . $billingAddress2 . "\n";
                        echo "Flat Unit No: " . $billingAddflatUnitNumber;
                        ?>
                    </textarea>
                    </div>
                    <div class="form-group">
                        <label for="shippingAddress">Shipping Address</label>
                        <textarea class="form-control" id="billingAddress" rows="3"><?php
                        echo "Address1: " . $shippingAddress1 . "\n";
                        echo "Address2: " . $shippingAddress2 . "\n";
                        echo "Flat Unit No: " . $shippingAddflatUnitNumber;
                        ?>
                        </textarea>
                    </div>

                    <!-- Payment Information -->
                    <div class="form-group">
                        <label for="cardNumber">Payment Method</label>
                        <select class="form-control" id="exampleSelect">
                            <option>Pay on delivery</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark">Place Order</button>
                </form>
            </div>

            <div class="col-md-4 summary mt-5">
                <h5><b>Order Summary</b></h5>
                <hr>
                <div class="row">
                    <div class="col">Items</div>
                    <div class="col text-right">&#8377;
                        <?php echo number_format($subTotal, 2); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">Shipping</div>
                    <div class="col text-right">&#8377;
                        <?php echo number_format($standardShippingCharge, 2); ?>
                    </div>
                </div>
                <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                    <div class="col">Total</div>
                    <div class="col text-right">&#8377;
                        <?php echo number_format($grandTotal, 2); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- remove this when testing done  because it show ids of db  -->
    <script>
        var cartData = <?php echo json_encode($cartData); ?>;
        console.log("Session Cart data : ");
        console.log(cartData);
    </script>

    <!-- send req to placed_order file  -->
    <script>
        $(document).ready(function () {
            $('form').submit(function (event) {
                // Prevent default form submission
                event.preventDefault();
                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: 'place_order.php', // PHP script to handle the insertion
                    success: function (response) {
                        var message = (response.trim() === 'Order placed successfully!') ?
                            alert(response)/*it shows order placed successfully! if true*/ :
                            alert(response);/*it shows order already placed! if false*/
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred while processing your request. Please try again later.');
                    }
                });
            });
        });
    </script>


</body>

</html>