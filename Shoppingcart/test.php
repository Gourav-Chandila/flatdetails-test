<?php
// Include the database connection file
require '../phpfiles/db_connect.php';

// Fetch corresponding data from product_assoc and product_feature_appl tables
$Sql = "SELECT
            pa.PRODUCT_ID AS main_product_id,
            pa.PRODUCT_ID_TO AS related_product_id,
            pfa.PRODUCT_FEATURE_ID AS standard_feature_id,
            pa.PRODUCT_ID_TO
        FROM
            product_assoc pa
        LEFT JOIN
            product_feature_appl pfa ON pa.PRODUCT_ID_TO = pfa.PRODUCT_ID
            WHERE pa.PRODUCT_ID LIKE 'MN_SH_TT%'
            ";

$Result = mysqli_query($conn, $Sql);

// Check if there's an error with the query
if ($Result) {
    $data = array(); // Initialize an array to store the result data
    while ($Row = mysqli_fetch_assoc($Result)) {
        $mainProductID = $Row['main_product_id'];
        $relatedProductID = $Row['PRODUCT_ID_TO'];
        $standardFeatureID = $Row['standard_feature_id'];

        // Fetch additional information from the product_feature table for standard feature
        $standardFeatureSql = "SELECT pa.PRODUCT_ID, pf.PRODUCT_FEATURE_ID, pf.DESCRIPTION, pf.PRODUCT_FEATURE_TYPE_ID,p.MEDIUM_IMAGE_URL,pp.PRICE,p.PRODUCT_NAME,pf.REDIRECT_URI,p.IS_VIRTUAL
        FROM product_assoc pa
        LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = pa.PRODUCT_ID
        LEFT JOIN product_feature pf ON pfa.PRODUCT_FEATURE_ID = pf.PRODUCT_FEATURE_ID
        LEFT JOIN product p ON pfa.PRODUCT_ID = p.PRODUCT_ID
        LEFT JOIN product_price pp ON pp.PRODUCT_ID = pa.PRODUCT_ID
        WHERE pa.PRODUCT_ID = '$mainProductID'";

        $standardFeatureResult = mysqli_query($conn, $standardFeatureSql);
        $standardFeatureInfo = mysqli_fetch_assoc($standardFeatureResult);

        // Check if the main product already exists in the data array
        $mainProductExists = false;
        foreach ($data as &$item) {
            if ($item['STANDARD_FEATURE']['MAIN_PRODUCT_ID'] === $mainProductID) {
                $mainProductExists = true;

                // Check if the SELECTABLE_FEATURES array exists
                if (!isset($item['SELECTABLE_FEATURES'])) {
                    $item['SELECTABLE_FEATURES'] = array();
                }

                // Fetch additional information from the product_feature table
                $featuresSql = "SELECT pf.PRODUCT_FEATURE_ID,p.PRODUCT_ID,pf.PRODUCT_FEATURE_TYPE_ID,pf.DESCRIPTION,
                 p.MEDIUM_IMAGE_URL,pp.PRICE,p.PRODUCT_NAME,p.IS_VIRTUAL,p.IS_VARIANT,pf.REDIRECT_URI,pf.PRODUCT_FEATURE_ID
                FROM product_feature pf
                LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_FEATURE_ID =pf.PRODUCT_FEATURE_ID
                LEFT JOIN product p ON p.PRODUCT_ID= pfa.PRODUCT_ID
                LEFT JOIN product_price pp ON pp.PRODUCT_ID= pfa.PRODUCT_ID
                WHERE pf.PRODUCT_FEATURE_ID='$standardFeatureID'";
                $featuresResult = mysqli_query($conn, $featuresSql);

                if ($featuresResult) {
                    $featureInfo = mysqli_fetch_assoc($featuresResult);

                    // Add the feature with additional information to the SELECTABLE_FEATURES array
                    $item['SELECTABLE_FEATURES'][] = array(
                        'PRODUCT_ID_TO' => $relatedProductID,
                        'PRODUCT_FEATURE_ID' => $standardFeatureID,
                        'PRODUCT_FEATURE_TYPE_ID' => $featureInfo['PRODUCT_FEATURE_TYPE_ID'],
                        'PRODUCT_NAME' => $featureInfo['PRODUCT_NAME'],
                        'PRODUCT_IMAGE' => $featureInfo['MEDIUM_IMAGE_URL'],
                        'PRODUCT_PRICE' => $featureInfo['PRICE'],
                        // 'REDIRECT_FILE_NAME' => $standardFeatureInfo['REDIRECT_URI'],
                        'DESCRIPTION' => $featureInfo['DESCRIPTION']
                    );
                } else {
                    // Handle the case where there's an error with the features query
                    echo '<script>';
                    echo 'console.error("Error in SQL query: ' . mysqli_error($conn) . '");';
                    echo '</script>';
                }
            }
        }

        // If the main product doesn't exist in the data array, create a new entry
        if (!$mainProductExists) {
            // Fetch additional information from the product_feature table
            $featuresSql = "SELECT pf.PRODUCT_FEATURE_ID,
            
             pf.PRODUCT_FEATURE_TYPE_ID,pf.DESCRIPTION,p.MEDIUM_IMAGE_URL,pp.PRICE,p.PRODUCT_NAME,
             p.IS_VIRTUAL,p.IS_VARIANT,pf.REDIRECT_URI,pf.PRODUCT_FEATURE_ID
            FROM product_feature pf
            LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_FEATURE_ID =pf.PRODUCT_FEATURE_ID
            LEFT JOIN product p ON p.PRODUCT_ID= pfa.PRODUCT_ID
            LEFT JOIN product_price pp ON pp.PRODUCT_ID= pfa.PRODUCT_ID
            WHERE pf.PRODUCT_FEATURE_ID='$standardFeatureID'";
            $featuresResult = mysqli_query($conn, $featuresSql);
            $featureInfo = mysqli_fetch_assoc($featuresResult);
            $data[] = array(
                'STANDARD_FEATURE' => array(
                    'MAIN_PRODUCT_ID' => $mainProductID,
                    'PRODUCT_FEATURE_ID' => $standardFeatureInfo['PRODUCT_FEATURE_ID'],
                    'PRODUCT_FEATURE_TYPE_ID' => $standardFeatureInfo['PRODUCT_FEATURE_TYPE_ID'],
                    'PRODUCT_NAME' => $standardFeatureInfo['PRODUCT_NAME'],
                    'VIRTUAL_PRODUCT' => $standardFeatureInfo['IS_VIRTUAL'],
                    'PRODUCT_IMAGE' => $standardFeatureInfo['MEDIUM_IMAGE_URL'],
                    'PRODUCT_PRICE' => $standardFeatureInfo['PRICE'],
                    'DESCRIPTION' => $standardFeatureInfo['DESCRIPTION']
                ),
                'SELECTABLE_FEATURES' => array(
                    array(
                        'PRODUCT_ID_TO' => $relatedProductID,
                        'PRODUCT_FEATURE_ID' => $standardFeatureID,
                        'PRODUCT_FEATURE_TYPE_ID' => $featureInfo['PRODUCT_FEATURE_TYPE_ID'],
                        'PRODUCT_NAME' => $featureInfo['PRODUCT_NAME'],
                        'PRODUCT_IMAGE' => $featureInfo['MEDIUM_IMAGE_URL'],
                        'PRODUCT_PRICE' => $featureInfo['PRICE'],
                        'DESCRIPTION' => $featureInfo['DESCRIPTION']
                    )
                )
            );
        }
    }

    // Output JSON response
    echo json_encode($data);
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ');';
    echo '</script>';

} else {
    // Handle the case where there's an error with the query
    echo '<script>';
    echo 'console.error("Error in SQL query: ' . mysqli_error($conn) . '");';
    echo '</script>';
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
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Mens formal shoes</title>

</head>

<body>

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
                        ${standardBackgroundColor ? `<div class="rounded-circle d-inline-block mr-1 p-2 border border-secondary" style="background-color: ${standardBackgroundColor}; width: 20px; height: 20px;" onclick="selectColor('${product.STANDARD_FEATURE.PRODUCT_IMAGE}', '${product.STANDARD_FEATURE.PRODUCT_NAME}', ${product.STANDARD_FEATURE.PRODUCT_PRICE}, ${cardIndex}),${product.STANDARD_FEATURE.PRODUCT_FEATURE_ID}"></div>` : ''}
                        ${selectableBackgroundElements}
                    </div>

                <div id="sizeContainer${cardIndex}"></div>

                    <p id="productPrice${cardIndex}" class="card-text">Price: ${product.STANDARD_FEATURE.PRODUCT_PRICE}</p>
                    <a href="${product.STANDARD_FEATURE.REDIRECT_FILE_NAME}" class="btn btn-primary">Details</a>
                </div>
            </div>
        </div>`;
        }

        // Modify the createSelectableBackgroundElements function to handle both COLOR and SIZE features
        function createSelectableBackgroundElements(selectableFeatures, cardIndex) {
            var backgroundElements = '';
            selectableFeatures.forEach(feature => {
                if (feature.PRODUCT_FEATURE_TYPE_ID === 'COLOR') {
                    backgroundElements += `<div class="rounded-circle d-inline-block mr-1 p-2 border border-secondary"  style="background-color: ${feature.DESCRIPTION}; width: 20px; height: 20px;" onclick="selectColor('${feature.PRODUCT_IMAGE}', '${feature.PRODUCT_NAME}', '${feature.PRODUCT_PRICE}', ${cardIndex},'${feature.PRODUCT_ID_TO}')"></div>`;
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

        function selectColor(newProductImage, newProductName, newProductPrice, cardIndex, PRODUCT_ID_TO) {
            var productImageElement = document.getElementById(`productImage${cardIndex}`);
            var productNameElement = document.getElementById(`productName${cardIndex}`);
            var productPriceElement = document.getElementById(`productPrice${cardIndex}`);
            var sizeContainerElement = document.getElementById(`sizeContainer${cardIndex}`);
            console.log(sizeContainerElement)
            productImageElement.src = `img/categories1-img/${newProductImage}`;
            productNameElement.innerHTML = newProductName;
            productPriceElement.innerHTML = `Price: ${newProductPrice}`;

            // Display sizes related to the selected color
            var relatedSizes = getRelatedSizes(PRODUCT_ID_TO);
            console.log(PRODUCT_ID_TO, " ", relatedSizes)
            sizeContainerElement.innerHTML = relatedSizes;
        }

        // Function to get sizes related to the selected color
        function getRelatedSizes(PRODUCT_ID_TO) {
            var relatedSizes = [];
            $.each(jsonData[0].SELECTABLE_FEATURES, function (index, product) {
                if (product.PRODUCT_FEATURE_TYPE_ID == "SIZE" && product.PRODUCT_ID_TO == PRODUCT_ID_TO) {
                    console.log(index, " ", product)
                    relatedSizes.push(product.DESCRIPTION);
                }
            });
            return relatedSizes;
        }

        // Iterate through the JSON data and create cards for each product
        jsonData.forEach((product, index) => {
            var productCard = createProductCard(product, index);
            $("#productCards").append(productCard);
        });
    </script>

</body>

</html>