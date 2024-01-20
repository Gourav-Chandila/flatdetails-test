<?php require 'productJsonData.php'; ?>
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
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test3</title>
</head>

<body>
    <!-- Add to cart icon -->
    <div class="cart-icon-container">
        <a href="cartPage.php" class="cart-link ">
            <i class="fa fa-cart-arrow-down fa-2x" aria-hidden="true"></i>
            <span id="cartCount" class="badge badge-danger ">0</span>
        </a>
    </div>


    <div class="container mt-4">
        <div class="row" id="productCards"></div>
    </div>

    <script>
        // Parse the JSON data received from the PHP script
        var jsonData = <?php echo json_encode($data); ?>;

        // Modify the createProductCard function to handle both COLOR and SIZE features
        function createProductCard(product, cardIndex) {
            var standardBackgroundColor = getBackgroundColor(product.STANDARD_FEATURE);
            var selectableBackgroundElements = createSelectableBackgroundElements(product.SELECTABLE_FEATURES, cardIndex);

            return `
            <div class="col-md-4 col-12 mt-5">
                <div class="card mb-4 shadow-sm">
                    <img id="productImage${cardIndex}" class="img-fluid" style="height: 100%;" src="img/categories1-img/${product.STANDARD_FEATURE.PRODUCT_IMAGE}">
                    <div class="card-body">
                        <h5 id="productName${cardIndex}" class="card-title">${product.STANDARD_FEATURE.PRODUCT_NAME}</h5>
                        <div class="">
                            ${standardBackgroundColor ? `<div class="rounded-circle d-inline-block mr-1 p-2 border border-secondary" style="background-color: ${standardBackgroundColor}; width: 20px; height: 20px;" onclick="selectColor('${product.STANDARD_FEATURE.PRODUCT_IMAGE}', '${product.STANDARD_FEATURE.PRODUCT_NAME}', ${product.STANDARD_FEATURE.PRODUCT_PRICE}, ${cardIndex}, '${product.STANDARD_FEATURE.PRODUCT_FEATURE_ID}')"></div>` : ''}
                            ${selectableBackgroundElements}
                        </div>

                        <div id="sizeContainer${cardIndex}"></div>

                        <p id="productPrice${cardIndex}" class="card-text">Price: ${product.STANDARD_FEATURE.PRODUCT_PRICE}</p>
                        <a href="" class="btn btn-dark ">Buy now</a>
            <a href="#" class="btn btn-dark" onclick="addToCart(${cardIndex})">Add to cart</a>
                    </div>
                </div>
            </div>`;
        }

        // Modify the createSelectableBackgroundElements function to handle both COLOR and SIZE features
        function createSelectableBackgroundElements(selectableFeatures, cardIndex) {
            var backgroundElements = '';
            selectableFeatures.forEach(feature => {
                if (feature.PRODUCT_FEATURE_TYPE_ID === 'COLOR') {
                    backgroundElements += `<div class="rounded-circle d-inline-block mr-1 p-2 border border-secondary"  style="background-color: ${feature.DESCRIPTION}; width: 20px; height: 20px;" onclick="selectColor('${feature.PRODUCT_IMAGE}', '${feature.PRODUCT_NAME}', '${feature.PRODUCT_PRICE}', ${cardIndex}, '${feature.PRODUCT_ID_TO}')"></div>`;
                }
            });

            return backgroundElements;
        }

        // Function to determine the background color based on the feature
        function getBackgroundColor(feature) {
            if (feature.PRODUCT_FEATURE_TYPE_ID === 'COLOR') {
                // Assuming description contains a valid hex color value
                return feature.DESCRIPTION;
            } else {
                return null; // Return null or any other value to indicate no color
            }
        }


        // Function to get sizes related to the selected color
        function getRelatedSizes(PRODUCT_ID_TO) {
            var relatedSizes = [];
            $.each(jsonData[0].SELECTABLE_FEATURES, function (index, product) {
                if (product.PRODUCT_FEATURE_TYPE_ID == "SIZE" && product.PRODUCT_ID_TO == PRODUCT_ID_TO) {
                    relatedSizes.push({
                        PRODUCT_NAME: product.PRODUCT_NAME,
                        PRODUCT_FEATURE_ID: product.PRODUCT_FEATURE_ID,
                        DESCRIPTION: product.DESCRIPTION,
                        DEFAULT_AMOUNT: product.DEFAULT_AMOUNT,
                        PRODUCT_IMAGE: product.PRODUCT_IMAGE
                    });
                }
            });
            return relatedSizes;
        }

        // Function to handle the click event
        function handleSizeClick(productName, selectedSize, productFeatureId, defaultAmount, productImage) {
            // Do something with the clicked size value and product feature ID
            console.log('Product name :', productName); // Corrected this line
            console.log('Clicked size:', selectedSize);
            console.log('Product Feature ID:', productFeatureId);
            console.log('Amount is:', defaultAmount); // Corrected this line
            console.log('Product image is :', productImage); // Corrected this line

            // Store selected size product image in the session
            storeSelectedSizeProductImage(productImage);
        }

        // Color select function
        function selectColor(newProductImage, newProductName, newProductPrice, cardIndex, PRODUCT_ID_TO) {
            var productImageElement = document.getElementById(`productImage${cardIndex}`);
            var productNameElement = document.getElementById(`productName${cardIndex}`);
            var productPriceElement = document.getElementById(`productPrice${cardIndex}`);
            var sizeContainerElement = document.getElementById(`sizeContainer${cardIndex}`);

            // Clear existing size containers
            sizeContainerElement.innerHTML = '';

            // Update product information
            productImageElement.src = `img/categories1-img/${newProductImage}`;
            productNameElement.innerHTML = newProductName;

            // Display sizes related to the selected color
            var relatedSizes = getRelatedSizes(PRODUCT_ID_TO);

            // Flag to track whether the first size has been selected
            var isFirstSizeSelected = false;

            relatedSizes.forEach(function (sizeData, index) {
                var sizeDiv = document.createElement('div');
                sizeDiv.className = 'border text-center'; // Add your desired background class here
                sizeDiv.style.width = '40px';
                sizeDiv.style.display = 'inline-block';
                sizeDiv.style.marginRight = '5px';
                sizeDiv.innerHTML = sizeData.DESCRIPTION;

                // Add a click event listener to each sizeDiv
                sizeDiv.addEventListener('click', function () {
                    var allSizeDivs = document.querySelectorAll('.border-danger');
                    allSizeDivs.forEach(function (otherSizeDiv) {
                        otherSizeDiv.classList.remove('border-danger');
                    });

                    // Toggle the "border-danger" class on the clicked size div
                    sizeDiv.classList.toggle('border-danger');

                    // Call a function with the clicked size value and product feature ID
                    handleSizeClick(sizeData.PRODUCT_NAME, sizeData.DESCRIPTION, sizeData.PRODUCT_FEATURE_ID, sizeData.DEFAULT_AMOUNT, sizeData.PRODUCT_IMAGE);
                    productPriceElement.innerHTML = `Price: ${sizeData.DEFAULT_AMOUNT}`;
                });

                // Automatically select the first size
                if (!isFirstSizeSelected && index === 0) {
                    sizeDiv.click(); // Simulate a click on the first size div
                    isFirstSizeSelected = true;
                }

                // Append the new div to the sizeContainerElement
                sizeContainerElement.appendChild(sizeDiv);
            });
        }



        // // cart items count 
        // var cartCountElement = document.getElementById('cartCount');
        // var cartCount = 0; // Initial cart count, you can set it to the actual count from your server or storage
        // function addToCart(cardIndex) {
        //     // Update the cart count
        //     cartCount++;
        //     // Update the cart count in the UI
        //     cartCountElement.innerHTML = cartCount;
        //     // Send an AJAX request to update cartCount on the server
        //     $.ajax({
        //         type: 'POST',
        //         url: 'updateCartCount.php',
        //         data: { cartCount: cartCount },
        //         success: function (response) {
        //             console.log('Cart count updated on the server.');
        //         },
        //         error: function (error) {
        //             console.error('Error updating cart count:', error);
        //         }
        //     });

        //     console.log(`Product at card index ${cardIndex} added to the cart.`);
        // }



        // Function to store selected size product image in the session
        function storeSelectedSizeProductImage(selectedSizeProductImage) {
            $.ajax({
                type: 'POST',
                url: 'updateCartCount.php', // Change the URL to your server script
                data: { selectedSizeProductImage: selectedSizeProductImage },
                success: function (response) {
                    if (response === "Success") {
                        console.log('Selected size product image stored in the session.');
                    } else {
                        console.error('Error storing selected size product image:', response);
                    }
                },
                error: function (error) {
                    console.error('Error storing selected size product image:', error);
                }
            });
        }

        // Iterate through the JSON data and create cards for each product
        jsonData.forEach((product, index) => {
            var productCard = createProductCard(product, index);
            $("#productCards").append(productCard);
        });
    </script>

</body>

</html>