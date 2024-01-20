<?php require '../phpfiles/db_connect.php'; ?>
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
    <title>Mens shoes categories</title>
</head>

<body>

    <!-- including navbar -->
    <?php require '../phpfiles/navbar.php' ?>

    <div class="categoriesCollectionContainer">
        <div class="container-fluid mb-2 border-bottom border-dark">
            <div class="row justify-content-center my-5 p-4">
                <h2 class="h2 categoriesCollectionH2">CATEGORIES COLLECTION</h2>
            </div>
            <!-- Carousel -->
            <div class="container text-center my-3">
                <div class="row mx-auto my-auto">
                    <div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <?php
                            // SQL query to fetch product categories
                            $categorySql = "SELECT pcc.PROD_CATALOG_ID,pc.PRODUCT_CATEGORY_ID ,pc.CATEGORY_NAME,pc.CATEGORY_IMAGE_URL
                            FROM product_category  pc
                            JOIN prod_catalog_category pcc ON pcc.PRODUCT_CATEGORY_ID = pc.PRODUCT_CATEGORY_ID
                            WHERE pcc.PROD_CATALOG_ID='ShoesCatalog' AND pc.PRODUCT_CATEGORY_ID LIKE 'MN_SH%'
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
                                                echo '<img class="img-fluid" style="object-fit: cover; height: 100%;" src="img/categories-img/menCategoriesImgs/' . $row['CATEGORY_IMAGE_URL'] . '">';
                                                if ($row['CATEGORY_NAME'] === 'Men formal shoes') {

                                                    echo '<a class="btn btn-dark" href="menForamlShoes.php">' . $row['CATEGORY_NAME'] . '</a>';
                                                } else {
                                                    echo '<a class="btn btn-dark" href="menSportsShoes.php">' . $row['CATEGORY_NAME'] . '</a>';
                                                }

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