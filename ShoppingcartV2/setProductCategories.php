<?php
require '../phpfiles/insertData.php';
require '../phpfiles/generateUniqueId.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Start a transaction
        mysqli_begin_transaction($conn);
        // Get data from POST request
        $category_name = $_POST['category_name'];
        $category_description = $_POST['category_desc'];
        $category_image = $_POST['category_image'];

        // Generate unique 'PRODUCT_STORE_ID'
        $prodStoreId = generateUniqueId($conn, "STRE_ID", "product_store");
        echo "Product store id is :" . $prodStoreId;
        // Insert data into product_store table
        $insertprodCatalog = array(
            'PRODUCT_STORE_ID' => $prodStoreId,
            'PRIMARY_STORE_GROUP_ID' => '_NA_',
            'STORE_NAME' => 'FARIDABAD STORE 1'
        );
        insertData("product_store", $insertprodCatalog, $conn);

        // Generate unique 'PROD_CATALOG_ID'
        $prodCatalogId = generateUniqueId($conn, "PRO_CTLOG", "prod_catalog");
        echo "Prod Catalog id is :" . $prodCatalogId;
        // Insert data into prod_catalog table
        $insertprodCatalog = array(
            'PROD_CATALOG_ID' => $prodCatalogId,
            'CATALOG_NAME' => 'testCatalog'
        );
        insertData("prod_catalog", $insertprodCatalog, $conn);


        // Insert data into product_store_catalog table
        $insertProdStoreCatalog = array(
            'PRODUCT_STORE_ID' => $prodStoreId,
            'PROD_CATALOG_ID' => $prodCatalogId,
            'FROM_DATE' => ''
        );
        insertData("product_store_catalog", $insertProdStoreCatalog, $conn);



        //    include Product categories insert sql
        // require 'insertProductCategories.php';

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



        // Commit the transaction if everything is successful
        mysqli_commit($conn);
        // Display a success message if the categories details inserted successfully
        echo '<div class="alert alert-success alert-dismissible fade show" role="success" id="myAlert">
          <strong>&#128522;</strong> Categories details registered successfully.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
           <span aria-hidden="true">×</span>
           </button>
          </div>';
    } catch (Exception $e) {
        // An error occurred, roll back the transaction
        $conn->rollBack();

        // Log the exception to a file or print it for debugging
        error_log("Exception: " . $e->getMessage());
        mysqli_rollback($conn);
        // Display a error message if the product details is not inserted
        echo '<div class="alert alert-danger alert-dismissible fade show" role="success" id="myAlert">
      <strong>&#10071;</strong> There are some technical issue in registering categories details.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">×</span>
       </button>
      </div>';
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

                <?php
                $jsonSetCategoriesStructure = json_decode('[{"category_name":{"name":"Category Name : ","elementName":"category_name","elementIdName":"category_name","elementPlaceHolder":"Enter category name"}},
        {"category_desc":{"name":"Category description : ","elementName":"category_desc","elementIdName":"category_desc","elementPlaceHolder":"Enter category description"}},
        {"category_image":{"name":"Category image name : ","elementName":"category_image","elementIdName":"category_image","elementPlaceHolder":"Enter category image name"}}
        ]');
                // Counts elements in an array '$jsonSetCategoriesStructure'
                $itemCount = count($jsonSetCategoriesStructure);
                for ($i = 0; $i < $itemCount; $i++) {
                    // Display two items in one row
                    if ($i % 2 == 0) {
                        echo '<div class="row">';
                    }

                    echo '<div class="col-md-6">';
                    $field = $jsonSetCategoriesStructure[$i];
                    $formName = key($field);
                    $formData = current($field);
                    echo '<div class="form-group">';
                    echo '<label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                    echo '<input type="text" class="form-control" id="' . $formData->elementIdName . '" name="' . $formData->elementName . '" placeholder=" ' . $formData->elementPlaceHolder . '">';
                    echo '</div>';
                    echo '</div>';

                    // Close the row after displaying two items
                    if ($i % 2 == 1 || $i == $itemCount - 1) {
                        echo '</div>';
                    }
                }
                ?>
                <div class="row">
                    <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Submit</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Fetch Product Categories -->
    <div class="row justify-content-center">
        <div class="col-2 text-center p-2 mr-4 border">
            <a href="menCategories.php">Men</a>
        </div>
        <div class="col-2 text-center p-2  border">
            <a href="womenCategories.php">Women</a>
        </div>
    </div>

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
                            //         $categorySql = "SELECT PRODUCT_CATEGORY_ID, CATEGORY_NAME, LONG_DESCRIPTION, CATEGORY_IMAGE_URL 
                            // FROM product_category WHERE PRODUCT_CATEGORY_ID LIKE 'PRO_CT_ID000000000%'";
                            $categorySql = "SELECT pcc.PROD_CATALOG_ID,pc.PRODUCT_CATEGORY_ID ,pc.CATEGORY_NAME,pc.CATEGORY_IMAGE_URL
                            FROM product_category  pc
                            JOIN prod_catalog_category pcc ON pcc.PRODUCT_CATEGORY_ID = pc.PRODUCT_CATEGORY_ID
                            WHERE pcc.PROD_CATALOG_ID='ShoesCatalog'
                    ";
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
                                                echo '<div class="card card-body h-100">';
                                                echo '<img class="img-fluid" style="object-fit: cover; height: 100%;" src="img/categories-img/' . $row['CATEGORY_IMAGE_URL'] . '">';
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