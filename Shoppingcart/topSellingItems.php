<!-- FETCH TOP SELLING ITEMS FROM DB -->
<div class="topSellingItemContainer mt-5 ">
    <div class="container text-center my-3">
        <div class="row justify-content-center  ">
            <h2>Top <strong>Selling</strong> Items</h2>
        </div>
        <div class="row mx-auto mt-4">
            <?php
            require '../phpfiles/db_connect.php';

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
                    // Loop through the query resultsX
                    while ($row = mysqli_fetch_assoc($result)) {

                        // error_log('Product id is : '.$row['PRODUCT_ID'], 0);
                        // error_log('Product name is : '.$row['PRODUCT_NAME'], 0);
                        // error_log('Image url is : '.$row['MEDIUM_IMAGE_URL'], 0);
                        // error_log('Product price is : '.$row['PRICE'], 0);
            

                        // Decode the JSON string
                        $colorData = json_decode($row['COLOR_VALUE'], true);

                        // Output PRODUCT_ID and $colorData to the JavaScript console
                        echo '<script>';
                        echo 'console.log("' . $row['PRODUCT_ID'] . '", ' . json_encode($colorData) . ');';
                        echo '</script>';


                        echo '<div class="col-md-4 col-12">';
                        echo '<div class="card card-body card-bg-color">';
                        echo '<img class="img-fluid card-bg-color" src="img/top-selling-items/' . $row['MEDIUM_IMAGE_URL'] . '">';
                        echo '</div>';
                        echo '<div class="productDetails">';
                        echo '<div class="productName text-dark">' . $row['PRODUCT_NAME'] . '</div>';
                        echo '<div class="roundedProductPriceLabel text-dark">';
                        echo '<span>&#8377;' . $row['PRICE'] . '</span>';
                        echo '</div>';

                        // Display hex values if $colorData is not null
                        if ($colorData !== null) {
                            foreach ($colorData as $color) {
                                // Log the hex value to check if it's correct
                                error_log('Color name is : ' . $color['colorName'] . ' and ' . 'Hex value is : ' . $color['hexValue'], 0);

                                // Output the label with the correct background color
                                echo '<label class="rounded-circle bg-brown mr-1 p-2 border-dark border-2" style="background-color: ' . $color['hexValue'] . ';"></label>';
                            }
                        }

                        echo '</div>';
                        echo '</div>';
                    }
                } else {

                    echo '<p>No items there</p>';
                }
            }
            ?>
        </div>
    </div>
</div>