<!-- FETCH TOP SELLING ITEMS FROM DB -->
<div class="topSellingItemContainer mt-5">  
    <div class="container text-center my-3">
        <div class="row justify-content-center  ">
            <h2>Top <strong>Selling</strong> Items</h2>
        </div>
        <div class="row mx-auto my-auto">
            <?php
            require '../phpfiles/db_connect.php';
            // $topSellingItemsSql = "SELECT pd.PRODUCT_ID, pd.PRODUCT_NAME, pd.MEDIUM_IMAGE_URL, pp.PRICE, pf.DESCRIPTION
            //     FROM product pd
            //     JOIN product_price pp ON pp.PRODUCT_ID = pd.PRODUCT_ID
            //     JOIN product_feature_appl pfa ON pfa.PRODUCT_ID = pd.PRODUCT_ID
            //     JOIN product_feature pf ON pf.PRODUCT_FEATURE_ID = pfa.PRODUCT_FEATURE_ID   
            //     WHERE pd.PRODUCT_ID LIKE 'PROD_ID%' AND pf.PRODUCT_FEATURE_TYPE_ID = 'COLOR';";
            // require '../phpfiles/db_connect.php';
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
        GROUP BY pd.PRODUCT_ID
        ";
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