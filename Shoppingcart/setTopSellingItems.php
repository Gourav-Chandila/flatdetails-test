<?php

require '../phpfiles/insertData.php';
require '../phpfiles/generateUniqueId.php';
// require '../phpfiles/db_connect.php';


try {


    // Start a transaction
    mysqli_begin_transaction($conn);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedColors'])) {
        // Get the color data from the POST request
        $colorData = json_decode($_POST['selectedColors'], true);

        // Get user input from the form (outside the foreach loop)
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_size = $_POST['product_size'];
        echo "Product size is :" . $product_size;
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



        $prodFeatureCategoryId = generateUniqueId($conn, 'PROD_FE_CT', 'product_feature_category');
        echo "Prod feature category id is : " . $prodFeatureCategoryId;
        $insertProdFeatureCategory = array(
            'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
            'DESCRIPTION' => 'Test',
        );
        insertData("product_feature_category", $insertProdFeatureCategory, $conn);


















        // Insert product feature
        // $productFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product_feature');
        // echo "Prod feature id is : " . $productFeatureId;
        // $insertProductFeature = array(
        //     'PRODUCT_FEATURE_ID' => $productFeatureId,
        //     'PRODUCT_FEATURE_TYPE_ID' => 'COLOR',
        //     'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
        //     'DESCRIPTION' => 'PRODUCT_VARIANT',
        //     'FEATURE_VALUE' => json_encode($selectedColors), // Store as JSON in the database
        //     'UOM_ID' => 'INR'
        // );
        // insertData("product_feature", $insertProductFeature, $conn);




    

 
       













        // Insert product feature application
        // $insertProductFeatureAppl = array(
        //     'PRODUCT_ID' => $productId,
        //     'PRODUCT_FEATURE_ID' => $productFeatureId,
        //     'PRODUCT_FEATURE_APPL_TYPE_ID' => 'SELECTABLE_FEATURE'
        // );
        // insertData("product_feature_appl", $insertProductFeatureAppl, $conn);

        // Commit the transaction if everything is successful
        mysqli_commit($conn);
        // Display a success message if the product details inserted successfully
        echo '<div class="alert alert-success alert-dismissible fade show" role="success" id="myAlert">
     <strong>&#128522;</strong> Product details registered successfully.
     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
      </button>
     </div>';
    }
} catch (Exception $e) {
    // An error occurred, roll back the transaction
    $conn->rollBack();

    // Log the exception to a file or print it for debugging
    error_log("Exception: " . $e->getMessage());
    mysqli_rollback($conn);
    // Display a error message if the product details is not inserted
    echo '<div class="alert alert-danger alert-dismissible fade show" role="success" id="myAlert">
         <strong>&#10071;</strong> There are some technical issue in registering product details.
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
                                        {"product_colors":{"name":"Select product colors : ","elementName":"colorDropdown","elementIdName":"colorDropdown","elementPlaceHolder":""}},
                                        {"product_size":{"name":"Enter size of product : ","elementName":"product_size","elementIdName":"product_size","elementPlaceHolder":"Enter product size"}}]');
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

                    // Special case for the color dropdown
                    if ($formData->elementName == "colorDropdown") {
                        echo '<select id="' . $formData->elementIdName . '" class="js-example-basic-multiple form-control" name="' . $formData->elementName . '" multiple="multiple">';
                        echo '<option value="" selected disabled>Select color</option>';
                        echo '<option value="#ff0000" data-color="#ff0000" data-name="Red">Red</option>';
                        echo '<option value="#00ff00" data-color="#00ff00" data-name="Green">Green</option>';
                        echo '<option value="#0000ff" data-color="#0000ff" data-name="Blue">Blue</option>';
                        echo '<option value="#a3381d" data-color="#a3381d" data-name="Brown">Brown</option>';
                        echo '<option value="#fff6db" data-color="#fff6db" data-name="Light brown">Light brown</option>';
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
                    <input type="" name="selectedColors" id="selectedColors" />
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
                $topSellingItemsSql = "SELECT pd.PRODUCT_ID, pd.PRODUCT_NAME, pd.MEDIUM_IMAGE_URL, pp.PRICE, pf.FEATURE_VALUE
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
                            // error_log('Product color is : ' . $row['FEATURE_VALUE'], 0);
                
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
                            $colorData = json_decode($row['FEATURE_VALUE'], true);
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

                }
            }

            $('#colorDropdown').on('change', updateColorInput);
    });
    </script>
</body>

</html>