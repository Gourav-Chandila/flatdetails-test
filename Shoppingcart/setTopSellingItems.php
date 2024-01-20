<?php

require '../phpfiles/insertData.php';
require '../phpfiles/generateUniqueId.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedColors'])) {
    // Get the color data from the POST request
    $selectedColors = $_POST['selectedColors'];
    echo $selectedColors;
    // Get user input from the form (outside the foreach loop)
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $selectedSizes = $_POST['selectedSize'];
    echo $selectedSizes;

    $productId = generateUniqueId($conn, 'PROD_ID', 'product');
    // echo "Prod id is : " . $productId;


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



    $prodFeatureCategoryId = generateUniqueId($conn, 'PROD_FE_CT', 'product_feature_category');
    // echo "Prod feature category id is : " . $prodFeatureCategoryId;
    $insertProdFeatureCategory = array(
        'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
        'DESCRIPTION' => 'Test',
    );
    insertData("product_feature_category", $insertProdFeatureCategory, $conn);

















    // Array to store product feature IDs
    $productFeatureIds = [];
    // if (!empty($product_size)) {
    //     // Insert product feature for size
    //     $productFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product_feature');
    //     echo "<br>Prod feature id for size is : " . $productFeatureId;
    //     $insertProductFeature = array(
    //         'PRODUCT_FEATURE_ID' => $productFeatureId,
    //         'PRODUCT_FEATURE_TYPE_ID' => 'SIZE',
    //         'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
    //         'DESCRIPTION' => $product_size,
    //         'UOM_ID' => 'INR'
    //     );
    //     insertData("product_feature", $insertProductFeature, $conn);
    //     // Add the product feature ID to the array
    //     $productFeatureIds[] = $productFeatureId;
    // }


    if (!empty($selectedSizes)) {
        // Split selected colors into an array
        $sizesArray = explode(',', $selectedSizes);

        // Loop through each color
        foreach ($sizesArray as $size) {
            // Insert product feature for each color
            $productFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product_feature');
            echo "<br>Prod feature id for color is: " . $productFeatureId;

            // Extract hex value from the color
            $sizeValue = $size;

            // Insert product feature data
            $insertProductFeature = array(
                'PRODUCT_FEATURE_ID' => $productFeatureId,
                'PRODUCT_FEATURE_TYPE_ID' => 'SIZE',
                'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
                'DESCRIPTION' => $sizeValue,
                'UOM_ID' => 'INR'
            );

            // Insert data into the database
            insertData("product_feature", $insertProductFeature, $conn);

            // Add the product feature ID to the array
            $productFeatureIds[] = $productFeatureId;
        }
    }















    if (!empty($selectedColors)) {
        // Split selected colors into an array
        $colorsArray = explode(',', $selectedColors);

        // Loop through each color
        foreach ($colorsArray as $color) {
            // Insert product feature for each color
            $productFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product_feature');
            echo "<br>Prod feature id for color is: " . $productFeatureId;

            // Extract hex value from the color
            $hexValue = $color;

            // Insert product feature data
            $insertProductFeature = array(
                'PRODUCT_FEATURE_ID' => $productFeatureId,
                'PRODUCT_FEATURE_TYPE_ID' => 'COLOR',
                'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
                'DESCRIPTION' => $hexValue,
                'UOM_ID' => 'INR'
            );

            // Insert data into the database
            insertData("product_feature", $insertProductFeature, $conn);

            // Add the product feature ID to the array
            $productFeatureIds[] = $productFeatureId;
        }
    }

    // Insert product feature applications if there are feature IDs
    foreach ($productFeatureIds as $featureId) {
        $insertProductFeatureAppl = array(
            'PRODUCT_ID' => $productId,
            'PRODUCT_FEATURE_ID' => $featureId,
            'PRODUCT_FEATURE_APPL_TYPE_ID' => 'SELECTABLE_FEATURE'
        );
        insertData("product_feature_appl", $insertProductFeatureAppl, $conn);
    }

    // Commit the transaction if everything is successful
    // Display a success message if the product details inserted successfully
    echo '<div class="alert alert-success alert-dismissible fade show" role="success" id="myAlert">
     <strong>&#128522;</strong> Product details registered successfully.
     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
      </button>
     </div>';
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
</head>


<body>

    <!-- Display structure of Set top Selling Items   -->
    <div class="container mt-5 white">
        <div class="container-fluid ">
            <form action="setTopSellingItems.php" method="post">
                <div class="row justify-content-center">
                    <h2>Add Top Selling Items Details</h2>
                </div>
                <?php
                $jsonTopSellingStructure = json_decode('[{"product_name":{"name":"Product name : ","elementName":"product_name","elementIdName":"product_name","elementPlaceHolder":"Enter product name"}},
    {"product_desc":{"name":"Product description : ","elementName":"product_desc","elementIdName":"product_desc","elementPlaceHolder":"Enter product description"}},
    {"product_price":{"name":"Product price : ","elementName":"product_price","elementIdName":"product_price","elementPlaceHolder":"Enter product price"}},
    {"product_image":{"name":"Product image name : ","elementName":"product_image","elementIdName":"product_image","elementPlaceHolder":"Enter product image name"}},
                                {"product_colors":{"name":"Select product colors : ","elementName":"colorDropdown","elementIdName":"colorDropdown","options":[{"value":"#ff0000","data_name":"Red"},{"value":"#00ff00","data_name":"Green"},{"value":"#0000ff","data_name":"Blue"},{"value":"#a3381d","data_name":"Brown"},{"value":"#fff6db","data_name":"Light brown"}]}},
                                {"product_sizes":{"name":"Select product sizes : ","elementName":"sizeDropdown","elementIdName":"sizeDropdown","options":[{"value":"7","data_name":"7"},{"value":"8","data_name":"8"},{"value":"9","data_name":"9"},{"value":"10","data_name":"10"}]}}
                        
                            ]');
                            $productColorsImage = array(
                                '#fff6db' => 'menFormalSlipOnShoesBrown.jpg',
                            );
                // Counts elements in an array '$jsonSetCategoriesStructure'
                $itemCount = count($jsonTopSellingStructure);
                for ($i = 0; $i < $itemCount; $i++) {
                    // Display two items in one row
                    if ($i % 2 == 0) {
                        echo '<div class="row">';
                    }
                    echo '<div class="col-md-6">';
                    $field = $jsonTopSellingStructure[$i];
                    $formName = key($field);
                    $formData = current($field);
                    echo '<div class="form-group">';
                    echo '<label for="' . $formData->elementName . '">' . $formData->name . '</label>';

                    // Special case for the color and size dropdowns
                    if ($formData->elementName == "colorDropdown" || $formData->elementName == "sizeDropdown") {
                        echo '<select id="' . $formData->elementIdName . '" class="js-example-basic-multiple form-control" name="' . $formData->elementName . '" multiple="multiple">';
                        // Add options dynamically based on data
                        foreach ($formData->options as $option) {
                            echo '<option value="' . $option->value . '" data-color="' . $option->value . '">' . $option->data_name . '</option>';
                        }
                        echo '</select>';
                    } else {
                        echo '<input type="text" class="form-control" id="' . $formData->elementIdName . '" name="' . $formData->elementName . '" placeholder="' . $formData->elementPlaceHolder . '">';
                    }
                    echo '</div>';
                    echo '</div>';
                    // Close the row after displaying two items
                    if ($i % 2 == 1 || $i == $itemCount - 1) {
                        echo '</div>';
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <label for="selectedColors">Selected colors :</label>
                        <input type="" name="selectedColors" id="selectedColors" />
                    </div>
                    <div class="col-md-6">
                        <label for="selectedSize">Selected sizes :</label>
                        <input type="" name="selectedSize" id="selectedSize" />
                    </div>
                </div>
                <div class="row">
                    
                </div>
                <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Submit</button>
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
                $topSellingItemsSql = "SELECT
                        pd.PRODUCT_ID,
                        pd.PRODUCT_NAME,
                        pd.MEDIUM_IMAGE_URL,
                        pp.PRICE,
                        GROUP_CONCAT(pf.DESCRIPTION) AS COLORS
                    FROM product pd
                    JOIN product_price pp ON pp.PRODUCT_ID = pd.PRODUCT_ID
                    LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = pd.PRODUCT_ID
                    LEFT JOIN product_feature pf ON pf.PRODUCT_FEATURE_ID = pfa.PRODUCT_FEATURE_ID
                    WHERE pd.PRODUCT_ID LIKE 'PROD_ID%' AND pf.PRODUCT_FEATURE_ID LIKE 'PROD_FE%'
                        AND (pf.PRODUCT_FEATURE_TYPE_ID = 'COLOR')
                    GROUP BY pd.PRODUCT_ID";
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
                            echo '<div class="col-md-4 col-12">';
                            echo '<div class="card card-body card-bg-color">';
                            echo '<img class="img-fluid card-bg-color" src="img/top-selling-items/' . $row['MEDIUM_IMAGE_URL'] . '">';
                            echo '</div>';
                            echo '<div class="productDetails">';
                            echo '<div class="productName text-dark">' . $row['PRODUCT_NAME'] . '</div>';
                            echo '<div class="roundedProductPriceLabel text-dark">';
                            echo '<span>&#8377;' . $row['PRICE'] . '</span>';
                            echo '</div>';
                            // Explode the COLORS string
                            $hexValues = explode(',', $row['COLORS']);

                            // Loop through each color
                            foreach ($hexValues as $color) {
                                echo '<label class="rounded-circle bg-brown mr-1 p-2 border-dark border-2" style="background-color: ' . $color . ';"></label>';
                            }

                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="container">
                    <div class="row justify-content-center">
                    <h6>No items there</h4>
                    </div>
                    </div>';
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

            //This shows rounded bg color 
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

                // Log selected colors before updating the hidden input
                // console.log('Selected Colors before update: ', selectedColors);

                if (selectedColors && selectedColors.length > 0) {
                    // Update the hidden input value with JSON-encoded string
                    $('#selectedColors').val(selectedColors);

                    // Log the updated hidden input value
                    // console.log('Updated Hidden Input Value: ', selectedColors);
                }
            }


            function updateSizeInput() {
                var selectedSize = $('#sizeDropdown').val();

                // Log selected colors before updating the hidden input
                // console.log('Selected Colors before update: ', selectedSize);

                if (selectedSize && selectedSize.length > 0) {

                    $('#selectedSize').val(selectedSize);

                    // Log the updated hidden input value
                    // console.log('Updated Hidden Input Value: ', selectedSize);
                }
            }

            $('#colorDropdown').on('change', updateColorInput);
            $('#sizeDropdown').on('change', updateSizeInput);
        });
    </script>

</body>

</html>