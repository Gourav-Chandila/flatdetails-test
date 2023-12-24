<?php
require '../phpfiles/generateDateTime.php';
require '../phpfiles/generateUniqueId.php';
require '../phpfiles/insertData.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get data from POST request
        $category_name = $_POST['category_name'];
        $category_description = $_POST['category_desc'];
        $category_image = $_POST['category_image'];

        // Generate unique IDs
        $prodCatalogId = generateUniqueId($conn, "PRO_CTLOG", "prod_catalog");
        echo "Prod Catalog id is :" . $prodCatalogId;

        // Insert data into prod_catalog table
        $insertprodCatalog = array(
            'PROD_CATALOG_ID' => $prodCatalogId,
            'CATALOG_NAME' => 'testCatalog'
        );
        insertData("prod_catalog", $insertprodCatalog, $conn);

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

        // Debugging information
        echo "Debugging: prodCatalogId=$prodCatalogId, productCategoryId=$productCategoryId";

        // Insert data into prod_catalog_category table
        $insertprodCatalog = array(
            'PROD_CATALOG_ID' => $prodCatalogId,
            'PRODUCT_CATEGORY_ID' => $productCategoryId,
            'PROD_CATALOG_CATEGORY_TYPE_ID' => 'PCCT_BEST_SELL',
            'FROM_DATE' => '',
            'THRU_DATE' => '',
        );
        insertData("prod_catalog_category", $insertprodCatalog, $conn);

        // Generate unique product feature category ID
        $productFeatureCategoryId = generateUniqueId($conn, 'PRoFE_CT_ID', 'product_feature_category_appl');

        // Insert data into product_feature_category_appl table
        $insertProductFeatureCategoryAppl = array(
            'PRODUCT_CATEGORY_ID' => $productCategoryId,
            'PRODUCT_FEATURE_CATEGORY_ID' => 'TEXT',
            'FROM_DATE' => '',
            'THRU_DATE' => ''
        );
        insertData("product_feature_category_appl", $insertProductFeatureCategoryAppl, $conn);
    } catch (Exception $e) {
        // Log the error
        error_log('Error: ' . $e->getMessage());
        // You can also redirect the user to an error page or show a friendly error message
        echo 'An error occurred. Please try again later.';
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set product categories</title>
</head>

<body>

    <!-- Display structure of Set top Selling Items   -->
    <div class="container mt-5 white">
        <div class="container-fluid ">

            <form action="setProductCategories.php" method="post">
                <div class="row justify-content-center">
                    <h2>Set product categories Details</h2>
                </div>
                <!-- Set category name -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_name">Category name :</label>
                            <input type="text" class="form-control" id="category_name" name="category_name"
                                placeholder="Enter category name">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="category_desc">Category description :</label>
                            <input type="text" class="form-control" id="category_desc" name="category_desc"
                                placeholder="Enter category description ">
                        </div>
                    </div>

                </div>
                <!-- Row of  category image name-->
                <div class="row">
                    <!-- Set category image name -->
                    <div class="col-md-6">
                        <label for="custom-file-input">Category image name :</label>
                        <div class="">
                            <div class="custom-file">
                                <input type="text" class="form-control" id="category_image" name="category_image"
                                    placeholder="Enter category image name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Submit</button>
                </div>
            </form>
        </div>
    </div>




    <!-- Fetch Product Categories -->
    <div class="categoriesCollectionContainer">
        <div class="container-fluid mb-2 border-bottom border-dark">
            <div class="row justify-content-center p-4">
                <h2 class="h2 categoriesCollectionH2">CATEGORIES COLLECTION</h2>
            </div>
            <!-- Carousel -->
            <div class="container text-center my-3">
                <div class="row mx-auto my-auto">
                    <div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">

                            <?php
                            // SQL query to fetch product categories
                            $categorySql = "SELECT PRODUCT_CATEGORY_ID, CATEGORY_NAME, LONG_DESCRIPTION, CATEGORY_IMAGE_URL 
                    FROM product_category WHERE PRODUCT_CATEGORY_ID LIKE 'PRO_CT_ID00000000007%'";
                            // Execute the query and fetch results
                            $result = mysqli_query($conn, $categorySql);

                            // Check if there's an error with the query
                            if (!$result) {
                                error_log("There is a problem with the query: " . mysqli_error($conn));
                            } else {
                                $rowCount = mysqli_num_rows($result);

                                // Check if there are records
                                if ($rowCount > 0) {
                                    $itemsPerSlide = 3;
                                    $totalSlides = ceil($rowCount / $itemsPerSlide);
                                    $activeClass = 'active';

                                    // Loop through each slide
                                    for ($i = 0; $i < $totalSlides; $i++) {
                                        echo '<div class="carousel-item ' . $activeClass . '">';
                                        echo '<div class="row">';

                                        // Loop through each item in the slide
                                        for ($j = 0; $j < $itemsPerSlide; $j++) {
                                            $row = mysqli_fetch_assoc($result);
                                            if ($row) {
                                                echo '<div class="col-md-4 col-12">';
                                                echo '<div class="card card-body card-bg-color">';
                                                echo '<img class="img-fluid card-bg-color" src="img/categories-img/' . $row['CATEGORY_IMAGE_URL'] . '">';
                                                echo '<button class="btn btn-dark">' . $row['CATEGORY_NAME'] . '</button>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }

                                        echo '</div>';
                                        echo '</div>';
                                        $activeClass = ''; // Remove active class for subsequent items
                                    }
                                } else {
                                    echo '<p>No  items there.</p>';
                                }
                            }
                            ?>
                        </div>

                        <!-- Carousel navigation controls -->
                        <a class="carousel-control-prev w-auto" href="#recipeCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark border border-dark rounded-circle"
                                aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next w-auto" href="#recipeCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon bg-dark border border-dark rounded-circle"
                                aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div> <!-- End Carousel -->
        </div> <!-- End categoriesCollectionContainer -->
    </div><!-- End categoriesCollectionContainer -->

</body>

</html>