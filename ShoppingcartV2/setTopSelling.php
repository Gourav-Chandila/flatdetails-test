<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/setTopSellingItems.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Top selling items</title>
    <style>

    </style>
</head>

<body>

    <?php
    $jsonTopSellingStructure = json_decode('[{"product_name":{"name":"Product name : ","elementName":"product_name","elementIdName":"product_name","elementPlaceHolder":"Enter product name"}},
    {"product_desc":{"name":"Product description : ","elementName":"product_desc","elementIdName":"product_desc","elementPlaceHolder":"Enter product description"}},
    {"product_price":{"name":"Product price : ","elementName":"product_price","elementIdName":"product_price","elementPlaceHolder":"Enter product price"}},
    {"product_image":{"name":"Product image name : ","elementName":"product_image","elementIdName":"product_image","elementPlaceHolder":"Enter product image name"}},
                                {"product_colors":{"name":"Select product colors : ","elementName":"colorDropdown","elementIdName":"colorDropdown","options":[{"value":"#ff0000","data_name":"Red"},{"value":"#00ff00","data_name":"Green"},{"value":"#0000ff","data_name":"Blue"},{"value":"#a3381d","data_name":"Brown"},{"value":"#fff6db","data_name":"Light brown"}]}},
                                {"product_sizes":{"name":"Select product sizes : ","elementName":"sizeDropdown","elementIdName":"sizeDropdown","options":[{"value":"7","data_name":"7"},{"value":"8","data_name":"8"},{"value":"9","data_name":"9"},{"value":"10","data_name":"10"}]}}
                        
                            ]');
    // Counts elements in an array '$jsonSetCategoriesStructure'
    $itemCount = count($jsonTopSellingStructure);
    for ($i = 0; $i < $itemCount; $i++) {
        // Display two items in one row
        if ($i % 2 == 0) {
            echo '<div class="row">';
        }
        echo '<div class="col-md-6">';
        $field = $jsonTopSellingStructure[$i];
        $formName = key($field);
        $formData = current($field);
        echo '<div class="form-group">';
        echo '<label for="' . $formData->elementName . '">' . $formData->name . '</label>';

        // Special case for the color and size dropdowns
        if ($formData->elementName == "colorDropdown" || $formData->elementName == "sizeDropdown") {
            echo '<select id="' . $formData->elementIdName . '" class="js-example-basic-multiple form-control" name="' . $formData->elementName . '" multiple="multiple">';
            // Add options dynamically based on data
            foreach ($formData->options as $option) {
                echo '<option value="' . $option->value . '" data-color="' . $option->value . '">' . $option->data_name . '</option>';
            }
            echo '</select>';
        } else {
            echo '<input type="text" class="form-control" id="' . $formData->elementIdName . '" name="' . $formData->elementName . '" placeholder="' . $formData->elementPlaceHolder . '">';
        }
        echo '</div>';
        echo '</div>';
        // Close the row after displaying two items
        if ($i % 2 == 1 || $i == $itemCount - 1) {
            echo '</div>';
        }
    }
    ?>
    <div class="row">
        <input type="" name="selectedColors" id="selectedColors" />
        <input type="" name="selectedSize" id="selectedSize" />
        <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Submit</button>
    </div>




    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                templateResult: formatColor,
                templateSelection: formatColor,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function formatColor(state) {
                if (!state.id) {
                    return state.text;
                }
                return $(
                    '<span class="d-inline-block  rounded-circle mx-2" style="width: 15px; height: 15px; background-color: ' +
                    $(state.element).data('color') +
                    '"></span> <span style="color: black;">' +
                    state.text +
                    '</span>'
                );
            }

            function updateColorInput() {
                var selectedColors = $('#colorDropdown').val();

                // Log selected colors before updating the hidden input
                // console.log('Selected Colors before update: ', selectedColors);

                if (selectedColors && selectedColors.length > 0) {
                    // Update the hidden input value with JSON-encoded string
                    $('#selectedColors').val(selectedColors);

                    // Log the updated hidden input value
                    // console.log('Updated Hidden Input Value: ', selectedColors);
                }
            }


            function updateSizeInput() {
                var selectedSize = $('#sizeDropdown').val();

                // Log selected colors before updating the hidden input
                // console.log('Selected Colors before update: ', selectedSize);

                if (selectedSize && selectedSize.length > 0) {
                    // Update the hidden input value with JSON-encoded string
                    $('#selectedSize').val(selectedSize);

                    // Log the updated hidden input value
                    // console.log('Updated Hidden Input Value: ', selectedSize);
                }
            }

            $('#colorDropdown').on('change', updateColorInput);
            $('#sizeDropdown').on('change', updateSizeInput);
        });
    </script>


</body>

</html>