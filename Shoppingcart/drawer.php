<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Always Open Drawer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <style>
        body {
            padding-top: 56px;

        }

        #sticky-sidebar {
            top: 56px;
            
        }
    </style>
</head>

<body>
    <?php require 'navbar.php'; ?>
    <div class="container-fluid ">
        <div class="row ">
            <div class="col-2 px-1 sidebarColor position-fixed" id="sticky-sidebar">
                <div class="nav flex-column flex-nowrap vh-100 overflow-auto text-white p-2">
                     <!-- PRICE  -->
            <div class="row border-bottom border-dark">
                <div class="col-2 p-0 py-2 ">
                    <details>
                        <summary class="">PRICE</summary>
                        <div class="row ">
                            <div class="col-10">
                                <input type="range" class="form-control-range" id="formControlRange">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <input type="text" class="form-control form-control-sm border-0 rounded-0"
                                    placeholder="">
                            </div>

                            <div class="col-1 d-flex align-items-center justify-content-center">
                                <span class="">to</span>
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control form-control-sm border-0 rounded-0"
                                    placeholder="">
                            </div>

                            <div class="col-auto">

                            </div>
                        </div>

                    </details>

                </div>

            </div>
                    <a href="./" class="nav-link">Link</a>
                    <a href="./" class="nav-link">Link</a>
                </div>
            </div>
            <div class="col-10 offset-2 " id="main">
                <h1>Main Area</h1> ...
                <p>Sriracha biodiesel taxidermy organic post-ironic, Intelligentsia salvia mustache 90's code editing
                    brunch. Butcher polaroid VHS art party, hashtag Brooklyn deep v PBR narwhal sustainable mixtape swag
                    wolf squid tote bag. Tote bag cronut semiotics, raw denim deep v taxidermy messenger bag. Tofu YOLO
                    Etsy, direct trade ethical Odd Future jean shorts paleo. Forage Shoreditch tousled aesthetic irony,
                    street art organic Bushwick artisan cliche semiotics ugh synth chillwave meditation. Shabby chic
                    lomo plaid vinyl chambray Vice. Vice sustainable cardigan, Williamsburg master cleanse hella DIY
                    90's blog.</p>
                <p>Sriracha biodiesel taxidermy organic post-ironic, Intelligentsia salvia mustache 90's code editing
                    brunch. Butcher polaroid VHS art party, hashtag Brooklyn deep v PBR narwhal sustainable mixtape swag
                    wolf squid tote bag. Tote bag cronut semiotics, raw denim deep v taxidermy messenger bag. Tofu YOLO
                    Etsy, direct trade ethical Odd Future jean shorts paleo. Forage Shoreditch tousled aesthetic irony,
                    street art organic Bushwick artisan cliche semiotics ugh synth chillwave meditation. Shabby chic
                    lomo plaid vinyl chambray Vice. Vice sustainable cardigan, Williamsburg master cleanse hella DIY
                    90's blog.</p>
            </div>
        </div>
    </div>
</body>

</html>