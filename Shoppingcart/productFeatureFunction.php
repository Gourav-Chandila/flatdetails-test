<?php
function insertProductFeatures($conn, $selectedColors, $product_size, $prodFeatureCategoryId)
{
    

    if (!empty($selectedColors) && !empty($product_size)) {
        // Generate unique IDs for color and size features
        $colorFeatureId = generateUniqueId($conn, 'PROD_FE_COLOR_', 'product_feature');
        // $sizeFeatureId = generateUniqueId($conn, 'PROD_FE_SIZE_', 'product_feature');

        // Insert data for color feature
        $insertColorFeature = array(
            'PRODUCT_FEATURE_ID' => $colorFeatureId,
            'PRODUCT_FEATURE_TYPE_ID' => 'COLOR',
            'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
            'DESCRIPTION' => 'PRODUCT_VARIANT',
            'FEATURE_VALUE' => json_encode($selectedColors), // Store as JSON in the database
            'UOM_ID' => 'INR'
        );

        $colorFeatureId = generateUniqueId($conn, 'PROD_FE_', 'product_feature');

        // Insert data for size feature
        $insertSizeFeature = array(
            'PRODUCT_FEATURE_ID' => $colorFeatureId,
            'PRODUCT_FEATURE_TYPE_ID' => 'SIZE',
            'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
            'DESCRIPTION' => 'PRODUCT_VARIANT',
            'FEATURE_VALUE' => $product_size, 
            'UOM_ID' => 'INR'
        );

        // Insert data into the database for both features
        insertData("product_feature", $insertColorFeature, $conn);
        insertData("product_feature", $insertSizeFeature, $conn);
    } elseif (!empty($selectedColors)) {
        // Insert data for color feature only
        $colorFeatureId = generateUniqueId($conn, 'PROD_FE_COLOR_', 'product_feature');
        echo "Prod feature id for color is : " . $colorFeatureId;
        $insertProductFeature = array(
            'PRODUCT_FEATURE_ID' => $colorFeatureId,
            'PRODUCT_FEATURE_TYPE_ID' => 'COLOR',
            'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
            'DESCRIPTION' => 'PRODUCT_VARIANT',
            'FEATURE_VALUE' => json_encode($selectedColors),
            'UOM_ID' => 'INR'
        );
        insertData("product_feature", $insertProductFeature, $conn);
    } elseif (!empty($product_size)) {
        // Insert data for size feature only
        $sizeFeatureId = generateUniqueId($conn, 'PROD_FE_SIZE_', 'product_feature');
        echo "Prod feature id for size is : " . $sizeFeatureId;
        $insertProductFeature = array(
            'PRODUCT_FEATURE_ID' => $sizeFeatureId,
            'PRODUCT_FEATURE_TYPE_ID' => 'SIZE',
            'PRODUCT_FEATURE_CATEGORY_ID' => $prodFeatureCategoryId,
            'DESCRIPTION' => 'PRODUCT_VARIANT',
            'FEATURE_VALUE' => $product_size,
            'UOM_ID' => 'INR'
        );
        insertData("product_feature", $insertProductFeature, $conn);
    }
}

// Example usage
// insertProductFeatures($conn, $selectedColors, $product_size, $prodFeatureCategoryId);
?>

require 'productFeatureFunction.php';
        insertProductFeatures($conn, $selectedColors, $product_size, $prodFeatureCategoryId);
