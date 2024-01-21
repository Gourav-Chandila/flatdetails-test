<?php
// Generate unique product category ID
$productCategoryId = generateUniqueId($conn, 'PRO_CT_ID', 'product_category');
echo "Product Category Id is : " . $productCategoryId;
// Insert data into product_category table
$insertProductCategory = array(
    'PRODUCT_CATEGORY_ID' => $productCategoryId,
    'CATEGORY_NAME' => $category_name,
    'LONG_DESCRIPTION' => $category_description,
    'CATEGORY_IMAGE_URL' => $category_image
);
insertData("product_category", $insertProductCategory, $conn);

// // Debugging information
// echo "Debugging: prodCatalogId=$prodCatalogId, productCategoryId=$productCategoryId";

// // Insert data into prod_catalog_category table
// $insertprodCatalog = array(
//     'PROD_CATALOG_ID' => $prodCatalogId,
//     'PRODUCT_CATEGORY_ID' => $productCategoryId,
//     'PROD_CATALOG_CATEGORY_TYPE_ID' => 'PCCT_BEST_SELL',
//     'FROM_DATE' => '',
//     'THRU_DATE' => '',
// );
// insertData("prod_catalog_category", $insertprodCatalog, $conn);

// // Generate unique product feature category ID
// $productFeatureCategoryId = generateUniqueId($conn, 'PRoFE_CT_ID', 'product_feature_category_appl');

// // Insert data into product_feature_category_appl table
// $insertProductFeatureCategoryAppl = array(
//     'PRODUCT_CATEGORY_ID' => $productCategoryId,
//     'PRODUCT_FEATURE_CATEGORY_ID' => 'TEXT',
//     'FROM_DATE' => '',
//     'THRU_DATE' => ''
// );
// insertData("product_feature_category_appl", $insertProductFeatureCategoryAppl, $conn);
?>