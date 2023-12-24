<?php require '../phpfiles/db_connect.php' ?>
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