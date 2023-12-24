<?php
require '../phpfiles/generateDateTime.php';
require '../phpfiles/generateUniqueId.php';
require '../phpfiles/insertData.php';
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedColors'])) {
        // Get the color data from the POST request
        $colorData = json_decode($_POST['selectedColors'], true);

        // Get user input from the form (outside the foreach loop)
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];

        require '../phpfiles/db_connect.php';
        $productId = generateUniqueId($conn, 'PROD_ID', 'product');
        echo "Prod id is : " . $productId;

        // Collect all selected colors in an array
        $selectedColors = [];
        // it takes color name with its hex value and store in array
        foreach ($colorData as $color) {
            $colorName = $color['name'];
            $hexValue = $color['value'];

            $selectedColors[] = [
                'colorName' => $colorName,
                'hexValue' => $hexValue
            ];
        }

        // Insert product details
        $insertProductDetails = array(
            'PRODUCT_ID' => $productId,
            'PRODUCT_NAME' => $product_name,
            'PRODUCT_TYPE_ID' => 'FINISHED_GOOD',
            'PRIMARY_PRODUCT_CATEGORY_ID' => 'SHOES',
            'MEDIUM_IMAGE_URL' => $product_image,
            'IS_VIRTUAL' => 'N',
            'IS_VARIANT' => 'N'
        );
        insertData("product", $insertProductDetails, $conn);

        // Insert product price
        $insertProductPrice = array(
            'PRODUCT_ID' => $productId,
            'PRODUCT_PRICE_TYPE_ID' => 'DEFAULT_PRICE',
            'PRODUCT_PRICE_PURPOSE_ID' => 'PURCHASE',
            'CURRENCY_UOM_ID' => 'INR',
            'PRODUCT_STORE_GROUP_ID' => '_NA_',
            'PRICE' => $product_price
        );
        insertData("product_price", $insertProductPrice, $conn);

        // Insert product association
        $insertProductAssoc = array(
            'PRODUCT_ID' => $productId,
            'PRODUCT_ASSOC_TYPE_ID' => 'PRODUCT_VARIANT'
        );
        insertData("product_assoc", $insertProductAssoc, $conn);






        $productFeatureCategoryId = generateUniqueId($conn, 'PRODFE_CT', 'product_feature_category');
        echo "Prod feature category id is : " . $productFeatureCategoryId;
        $insertProductFeatureCategory = array(
            'PRODUCT_FEATURE_CATEGORY_ID' => $productFeatureCategoryId,
            'DESCRIPTION' => 'Test'

        );
        insertData("product_feature_category", $insertProductFeatureCategory, $conn);






        // Insert product feature
        $productFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product');
        echo "Prod feature id is : " . $productFeatureId;

        $insertProductFeature = array(
            'PRODUCT_FEATURE_ID' => $productFeatureId,
            'PRODUCT_FEATURE_TYPE_ID' => 'COLOR',
            'PRODUCT_FEATURE_CATEGORY_ID' => $productFeatureCategoryId, //change 'TEXT' to $productFeatureCategoryId
            'DESCRIPTION' => 'PRODUCT_VARIANT',
            'COLOR_VALUE' => json_encode($selectedColors), // Store as JSON in the database
            'UOM_ID' => 'INR'
        );
        insertData("product_feature", $insertProductFeature, $conn);

        // Insert product feature application
        $insertProductFeatureAppl = array(
            'PRODUCT_ID' => $productId,
            'PRODUCT_FEATURE_ID' => $productFeatureId,
            'PRODUCT_FEATURE_APPL_TYPE_ID' => 'SELECTABLE_FEATURE'
        );
        insertData("product_feature_appl", $insertProductFeatureAppl, $conn);
        error_log("Insert working properly", 0);
    }
} catch (Exception $e) {
    // Log the exception to a file or print it for debugging
    error_log("Exception: " . $e->getMessage());
    // You can also log the error to a specific file
    error_log("Exception: " . $e->getMessage(), 3, "error.log");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/setTopSellingItems.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Top selling items</title>
    <style>
        body {
            /* background-color: #fff6db; */
        }
    </style>

</head>


<body>

    <!-- Display structure of Set top Selling Items   -->
    <div class="container mt-5 white">
        <div class="container-fluid ">

            <form action="setTopSellingItems.php" method="post">
                <div class="row justify-content-center">
                    <h2>Add Top Selling Items Details</h2>
                </div>
                <!-- Set product name -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_name">Product name :</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                placeholder="Enter product name">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="product_desc">Product description :</label>
                            <input type="text" class="form-control" id="product_desc" name="product_desc"
                                placeholder="Enter product description ">
                        </div>
                    </div>

                </div>
                <!-- Row of color and product image name-->
                <div class="row">
                    <!-- Set product color-->
                    <div class="col-md-6">
                        <div class="form-group">

                            <input type="hidden" id="selectedColors" name="selectedColors"
                                value=""><!-- dont remove it -->
                            <label>Select product colors :</label>
                            <select id="colorDropdown" class="js-example-basic-multiple form-control"
                                name="colorDropdown" multiple="multiple">
                                <option value="" selected disabled>Select color</option>
                                <option value="#ff0000" data-color="#ff0000" data-name="Red">Red</option>
                                <option value="#00ff00" data-color="#00ff00" data-name="Green">Green</option>
                                <option value="#0000ff" data-color="#0000ff" data-name="Blue">Blue</option>
                                <option value="#a3381d" data-color="#a3381d" data-name="Brown">Brown</option>
                                <option value="#fff6db" data-color="#fff6db" data-name="Light brown">Light brown
                                </option>
                            </select>
                        </div>



                    </div>

                    <!-- Set product image name -->
                    <div class="col-md-6">
                        <label for="custom-file-input">Product image name :</label>
                        <div class="">
                            <div class="custom-file">
                                <input type="text" class="form-control" id="product_image" name="product_image"
                                    placeholder="Enter product image name">
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Set product price -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_price">Product price :</label>
                            <input type="text" class="form-control" id="product_price" name="product_price"
                                placeholder="Enter product price">
                        </div>
                    </div>
                    <div class="col-md-6">






                    </div>
                </div>

                <div class="row">

                    <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Submit</button>
                </div>
            </form>
        </div>
    </div>



    <!-- FETCH TOP SELLING ITEMS FROM DB -->
    <div class="topSellingItemContainer mt-5">
        <div class="container text-center my-3">
            <div class="row justify-content-center  ">
                <h2>Top <strong>Selling</strong> Items</h2>
            </div>
            <div class="row mx-auto my-auto">
                <?php
                // $topSellingItemsSql = "SELECT pd.PRODUCT_ID,pd.PRODUCT_NAME,MEDIUM_IMAGE_URL,pp.PRICE
                // FROM product pd
                // JOIN product_price pp ON pp.PRODUCT_ID=pd.PRODUCT_ID
                // WHERE pd.PRODUCT_ID LIKE 'PROD_ID_10%'";
                $topSellingItemsSql = "SELECT pd.PRODUCT_ID, pd.PRODUCT_NAME, pd.MEDIUM_IMAGE_URL, pp.PRICE, pf.COLOR_VALUE
                FROM product pd
                JOIN product_price pp ON pp.PRODUCT_ID = pd.PRODUCT_ID
                JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = pd.PRODUCT_ID
                JOIN product_feature pf ON pf.PRODUCT_FEATURE_ID = pfa.PRODUCT_FEATURE_ID   
                WHERE pd.PRODUCT_ID LIKE 'PROD_ID%' AND pf.PRODUCT_FEATURE_TYPE_ID = 'COLOR';";
                // Execute the query and fetch results
                $result = mysqli_query($conn, $topSellingItemsSql);

                if (!$result) {
                    error_log("There is a problem with the query: " . mysqli_error($conn));
                } else {
                    // Check if there are any rows in the result
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through the query results
                        while ($row = mysqli_fetch_assoc($result)) {

                            // error_log('Product id is : '.$row['PRODUCT_ID'], 0);
                            // error_log('Product name is : '.$row['PRODUCT_NAME'], 0);
                            // error_log('Image url is : '.$row['MEDIUM_IMAGE_URL'], 0);
                            // error_log('Product price is : '.$row['PRICE'], 0);
                            // error_log('Product color is : ' . $row['COLOR_VALUE'], 0);
                
                            echo '<div class="col-md-4 col-12">';
                            echo '<div class="card card-body card-bg-color">';
                            echo '<img class="img-fluid card-bg-color" src="img/top-selling-items/' . $row['MEDIUM_IMAGE_URL'] . '">';
                            echo '</div>';
                            echo '<div class="productDetails">';
                            echo '<div class="productName text-dark">' . $row['PRODUCT_NAME'] . '</div>';
                            echo '<div class="roundedProductPriceLabel text-dark">';
                            echo '<span>&#8377;' . $row['PRICE'] . '</span>';
                            echo '</div>';

                            // Decode the JSON string 
                            $colorData = json_decode($row['COLOR_VALUE'], true);
                            // Output PRODUCT_ID and $colorData to the JavaScript console
                            // echo '<script>';
                            // echo 'console.log("' . $row['PRODUCT_ID'] . '", ' . json_encode($colorData) . ');';
                            // echo '</script>';
                            // Display hex values if $colorData is not null
                            if ($colorData !== null) {
                                foreach ($colorData as $color) {
                                    // Log the hex value to check if it's correct
                                    // error_log('Color name is : ' . $color['colorName'] . ' and ' . 'Hex value is : ' . $color['hexValue'], 0);
                                    // Output the label with the correct background color
                                    echo '<label class="rounded-circle bg-brown mr-1 p-2 border-dark border-2" style="background-color: ' . $color['hexValue'] . ';"></label>';
                                }
                            }


                            echo '</div>';
                            echo '</div>';
                        }
                    } else {

                        echo '<p>No  items there.</p>';
                    }
                }
                ?>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function formatColor(state) {
                if (!state.id) {
                    return state.text;
                }
                return $(
                    '<span class="d-inline-block  rounded-circle mx-2" style="width: 15px; height: 15px; background-color: ' +
                    $(state.element).data('color') +
                    '"></span> <span style="color: black;">' +
                    state.text +
                    '</span>'
                );
            }

            function updateColorInput() {
                var selectedColors = $('#colorDropdown').val();

                if (selectedColors && selectedColors.length > 0) {
                    var colorData = selectedColors.map(function (color) {
                        var option = $('#colorDropdown option[value="' + color + '"]');
                        var hexValue = option.data('color');
                        var colorName = option.data('name');
                        return { name: colorName, value: hexValue };
                    });

                    // Update the hidden input value
                    $('#selectedColors').val(JSON.stringify(colorData));

                    // Submit the form
                    // $('form').submit();
                }
            }

            $('#colorDropdown').on('change', updateColorInput);
        });
    </script>


</body>

</html>