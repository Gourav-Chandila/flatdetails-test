<?php
// Fetch additional information from the product_feature table
$featuresSql = "SELECT pf.PRODUCT_FEATURE_ID,
             pf.PRODUCT_FEATURE_TYPE_ID,pf.DESCRIPTION,p.MEDIUM_IMAGE_URL,pp.PRICE,p.PRODUCT_NAME,
             p.IS_VIRTUAL,p.IS_VARIANT,pf.PRODUCT_FEATURE_ID,pf.DEFAULT_AMOUNT
            FROM product_feature pf
            LEFT JOIN product_feature_appl pfa ON pfa.PRODUCT_FEATURE_ID =pf.PRODUCT_FEATURE_ID
            LEFT JOIN product p ON p.PRODUCT_ID= pfa.PRODUCT_ID
            LEFT JOIN product_price pp ON pp.PRODUCT_ID= pfa.PRODUCT_ID
            WHERE pf.PRODUCT_FEATURE_ID='$standardFeatureID'";
$featuresResult = mysqli_query($conn, $featuresSql);
?>