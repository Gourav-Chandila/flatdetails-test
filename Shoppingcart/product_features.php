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
            WHERE pa.PRODUCT_ID LIKE 'MN_SH_SP%'
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
                $featuresSql = "SELECT pf.PRODUCT_FEATURE_ID,
                p.PRODUCT_ID,
                 pf.PRODUCT_FEATURE_TYPE_ID,
                 pf.DESCRIPTION,
                 p.MEDIUM_IMAGE_URL,
                 pp.PRICE,
                 p.PRODUCT_NAME,
                 p.IS_VIRTUAL,
                 p.IS_VARIANT,
                 pf.REDIRECT_URI,
                 pf.PRODUCT_FEATURE_ID
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
                        'PRODUCT_FEATURE_ID' => $standardFeatureID,
                        'PRODUCT_FEATURE_TYPE_ID' => $featureInfo['PRODUCT_FEATURE_TYPE_ID'],
                        'PRODUCT_NAME' => $featureInfo['PRODUCT_NAME'],
                        'PRODUCT_IMAGE' => $featureInfo['MEDIUM_IMAGE_URL'],
                        'PRODUCT_PRICE' => $featureInfo['PRICE'],
                        'REDIRECT_FILE_NAME' => $standardFeatureInfo['REDIRECT_URI'],
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
            
             pf.PRODUCT_FEATURE_TYPE_ID,
             pf.DESCRIPTION,
             p.MEDIUM_IMAGE_URL,
             pp.PRICE,
             p.PRODUCT_NAME,
             p.IS_VIRTUAL,
             p.IS_VARIANT,
             pf.REDIRECT_URI,
             pf.PRODUCT_FEATURE_ID
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
                    'REDIRECT_FILE_NAME' => $standardFeatureInfo['REDIRECT_URI'],
                    'DESCRIPTION' => $standardFeatureInfo['DESCRIPTION']
                ),
                'SELECTABLE_FEATURES' => array(
                    array(
                        'PRODUCT_FEATURE_ID' => $standardFeatureID,
                        'PRODUCT_FEATURE_TYPE_ID' => $featureInfo['PRODUCT_FEATURE_TYPE_ID'],
                        'PRODUCT_NAME' => $featureInfo['PRODUCT_NAME'],
                        'PRODUCT_IMAGE' => $featureInfo['MEDIUM_IMAGE_URL'],
                        'PRODUCT_PRICE' => $featureInfo['PRICE'],
                        'REDIRECT_FILE_NAME' => $standardFeatureInfo['REDIRECT_URI'],
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

// Close the connection
mysqli_close($conn);
?>