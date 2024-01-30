<!--  Carousel -->
<div id="carouselExampleSlidesOnly" class="carousel slide my-5" data-ride="carousel">
    <div class="carousel-inner">
        <?php
        $jsonCarousel = json_decode('[{"carouselItem1":{"elementName":"carouselItem1","elementIdName":"carouselItem1","carouselImageName":"Untitled_1920_x_1080px.webp","carouselStatus":"active"}},
                                 {"carouselItem2":{"elementName":"carouselItem2","elementIdName":"carouselItem2","carouselImageName":"image_2_e8cd9a28-00d1-4d4f-976c-d95b19ae2a66.webp","carouselStatus":""}},
                                 {"carouselItem3":{"elementName":"carouselItem3","elementIdName":"carouselItem3","carouselImageName":"image_3_15d2914c-cc2e-4b0a-9aa1-fdedc0e01bb6.webp","carouselStatus":""}},
                                 {"carouselItem4":{"elementName":"carouselItem4","elementIdName":"carouselItem4","carouselImageName":"image_4_7f3cc94c-6e7d-4adc-81f0-72ec0871b309.webp","carouselStatus":""}},
                                 {"carouselItem5":{"elementName":"carouselItem5","elementIdName":"carouselItem5","carouselImageName":"image_438dc762-6df4-4318-804b-97c1634f881a.webp","carouselStatus":""}}]');
        // Iterate through each item
        foreach ($jsonCarousel as $carouselItem) {
            $cardData = reset($carouselItem);
            $carouselStatus = $cardData->carouselStatus;
            echo '<div class="carousel-item ' . $carouselStatus . '">
            <img src="img/carosuel-img/' . $cardData->carouselImageName . '" class="d-block w-100" alt="...">
            </div>';
        }
        ?>
    </div>
</div> <!--  End of Carousel -->