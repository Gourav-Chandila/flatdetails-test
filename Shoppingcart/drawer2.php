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
    <title>Drawer2</title>
    <style>
        .container-fluid {
            margin-top: 100px;
            height: 500px;
            width: auto;
            background-color: green;
        }

        .container {
            height: 400px;
            width: auto;
            background-color: pink;
            /* background-color: #fff6db; */
        }

        .col-2 {
            background-color: #fff6db;
        }

        .container {
            /* background-color: red; */
        }
    </style>
</head>

<body>
    <!-- <?php require 'navbar.php'?> -->
    <div class="container-fluid">
        <div class="container ">
            <!-- PRICE  -->
            <div class="row ">
                <div class="col-2 p-0 py-2 border-bottom border-dark">
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








        </div>
    </div>













</body>

</html>