<?php
function getProductJson($productAssocProductId)
{
    require '../phpfiles/db_connect.php';
    // Fetch corresponding data from product_assoc and product_feature_appl tables
    $Sql = "SELECT
        pa.PRODUCT_ID AS main_product_id, pa.PRODUCT_ID_TO AS related_product_id,
        pfa.PRODUCT_FEATURE_ID AS standard_feature_id,
        pa.PRODUCT_ID_TO FROM product_assoc pa
        LEFT JOIN product_feature_appl pfa ON pa.PRODUCT_ID_TO = pfa.PRODUCT_ID
        WHERE pa.PRODUCT_ID LIKE '$productAssocProductId'
        ";
    $Result = mysqli_query($conn, $Sql);

    // Check if there's an error with the query
    if ($Result) {
        $data = array(); // Initialize an array to store the result data
        while ($Row = mysqli_fetch_assoc($Result)) {
            $mainProductID = $Row['main_product_id'];
            $relatedProductID = $Row['PRODUCT_ID_TO'];
            $standardFeatureID = $Row['standard_feature_id'];

            // genric SQL query to fetch product features and standard features
            $featureSql = "SELECT pf.PRODUCT_FEATURE_ID,
             pf.PRODUCT_FEATURE_TYPE_ID, pf.DESCRIPTION, p.MEDIUM_IMAGE_URL, pp.PRICE, p.PRODUCT_NAME,
             p.IS_VIRTUAL, p.IS_VARIANT, pf.PRODUCT_FEATURE_ID, pf.DEFAULT_AMOUNT
            FROM product_feature pf
            LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_FEATURE_ID = pf.PRODUCT_FEATURE_ID
            LEFT JOIN product p ON p.PRODUCT_ID = pfa.PRODUCT_ID
            LEFT JOIN product_price pp ON pp.PRODUCT_ID = pfa.PRODUCT_ID";

            // Fetch additional information from the product_feature table for standard feature
            $standardFeatureSql = $featureSql . " WHERE pfa.PRODUCT_ID = '$mainProductID'";
            $standardFeatureResult = mysqli_query($conn, $standardFeatureSql);
            $standardFeatureInfo = mysqli_fetch_assoc($standardFeatureResult);

            // Fetch additional information from the product_feature table
            $featuresSql = $featureSql . " WHERE pf.PRODUCT_FEATURE_ID = '$standardFeatureID'";
            $featuresResult = mysqli_query($conn, $featuresSql);



            //array where store objects
            $relatedProducts = array();
            while ($featureInfo = mysqli_fetch_assoc($featuresResult)) {
                $relatedProducts[] = array(
                    "PRODUCT_ID_TO" => $relatedProductID,
                    "PRODUCT_FEATURE_ID" => $featureInfo['PRODUCT_FEATURE_ID'],
                    "PRODUCT_FEATURE_TYPE_ID" => $featureInfo['PRODUCT_FEATURE_TYPE_ID'],
                    "PRODUCT_NAME" => $featureInfo['PRODUCT_NAME'],
                    "PRODUCT_IMAGE" => $featureInfo['MEDIUM_IMAGE_URL'],
                    // "PRODUCT_PRICE" => $featureInfo['PRICE'],
                    "DESCRIPTION" => $featureInfo['DESCRIPTION'],
                    "DEFAULT_AMOUNT" => $featureInfo['DEFAULT_AMOUNT']
                );
            }
            // Store main product information only once for each unique main product ID
            if (!isset($data[$mainProductID])) {
                $data[$mainProductID] = array(
                    "MAIN_PRODUCT_ID" => $mainProductID,
                    "PRODUCT_FEATURE_ID" => $standardFeatureInfo['PRODUCT_FEATURE_ID'],
                    "PRODUCT_FEATURE_TYPE_ID" => $standardFeatureInfo['PRODUCT_FEATURE_TYPE_ID'],
                    "PRODUCT_NAME" => $standardFeatureInfo['PRODUCT_NAME'],
                    "VIRTUAL_PRODUCT" => $standardFeatureInfo['IS_VIRTUAL'],
                    "PRODUCT_IMAGE" => $standardFeatureInfo['MEDIUM_IMAGE_URL'],
                    "PRODUCT_PRICE" => $standardFeatureInfo['PRICE'],
                    "DESCRIPTION" => $standardFeatureInfo['DESCRIPTION'],
                    "RELATED_PRODUCTS" => $relatedProducts
                );
            } else {
                // If the main product ID already exists, only add the related product information
                $data[$mainProductID]["RELATED_PRODUCTS"] = array_merge($data[$mainProductID]["RELATED_PRODUCTS"], $relatedProducts);
            }
        }
    }
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ');';
    echo '</script>';
    return $data;
}


// function call and usage 
// getProductJson("MN_SH_TT%");
?>