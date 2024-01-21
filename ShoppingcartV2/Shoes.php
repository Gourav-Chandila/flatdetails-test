<?php
require 'getProductJsonData.php';
// check is set for value in url parameter 
$for = isset($_GET['for']) ? $_GET['for'] : '';

//Function call to get json data
$data = getProductJson($for . '%');

// print the data in page
// echo json_encode($data);
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

    <!-- including functionCreateProductCard   file -->
    <script src="js/functionCreateProductCard.js"></script>
    <!-- including mainSelect color function file -->
    <script src="js/functionSelectMainColor.js"></script>

    <!-- including functionCreateRelatedColors function file   -->
    <script src="js/functionCreateRelatedColors.js"></script>

    <!-- including functionSelectRelatedColor  file   -->
    <script src="js/functionSelectRelatedColor.js"></script>

    <!-- including functionCreateRelatedSizes  file   -->
    <script src="js/functionCreateRelatedSizes.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test Shoes</title>
</head>

<body>

    <!-- Add to cart icon -->
    <div class="cart-icon-container">
        <a href="cartPage.php" class="cart-link ">
            <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
            <span id="cartCount" class="badge badge-danger ">0</span>
        </a>
    </div>

    <!-- Product card -->
    <div class="container mt-4">
        <div class="row" id="productCards"></div>
    </div>


    <!-- Color and size script -->
    <script>
        //Clear the session storage for only shows cart item every time when user refresh this page than sessionStorage is clear
        sessionStorage.clear()
        var jsonData = <?php echo json_encode($data); ?>;




        // Declare a variable to store the currently selected size element
        var selectedSizeElement = null;
        // Declare a variable to store the currently selected size information
        var selectedSize = {
            element: null,
            productImage: '',
            price: 0
        };
        // Declare an array to store the currently selected size information for each product card
        var selectedSizes = [];

        // Function for handling size
        function handleSizeClick(element, size, productImage, price, index) {
            var productPriceElement = document.getElementById(`productPrice${index}`);

            // Log the clicked size, color with price, and size product image
            console.log(`Clicked size is: ${size}`);
            console.log(`Color with price: ${price}`);
            console.log(`Size product image is: ${productImage}`);

            // Remove border style from the previously selected size element
            if (selectedSizes[index] && selectedSizes[index].element) {
                selectedSizes[index].element.style.border = '1px solid #e4e6eb';
            }

            // Set the border style to the clicked size element
            element.style.border = '1.5px solid red';

            // Update the currently selected size information for this product card
            selectedSizes[index] = {
                element: element,
                productImage: productImage,
                price: price
            };

            // Set the related size default_price
            productPriceElement.innerHTML = `Price: ${price}`;
        }



        // Iterate through jsonData objects and create product cards
        var productCardsContainer = $('#productCards');
        Object.values(jsonData).forEach(function (mainProduct, index) {
            var cardHtml = createProductCard(mainProduct, index);
            productCardsContainer.append(cardHtml);
            // function call selectColor
            selectColor(mainProduct, index);

        });
    </script>

    <!-- Link to external JavaScript file where ajax request for product data and cartCount-->
    <script src="js/addToCart.js"></script>

</body>

</html>