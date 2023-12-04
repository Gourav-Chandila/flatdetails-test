<?php

error_reporting(0);
?>
<?php
require 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if "partyId" is set in the URL parameters (query string)
    if (isset($_GET['partyId'])) {
        $partyIdFromUrl = mysqli_real_escape_string($conn, $_GET['partyId']);
        $uniquePID1 = $partyIdFromUrl;
        require 'generateUniqueId.php';
        require 'upload_document2.php';
    }
}
?>

<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <style>
        .document_container {
            width: 355px;
            height: 339px;
        }

        .Document_image {
            width: 340px;
            height: 314px;
        }

        .dotted-border {
            background-color: white;
            border: 2.5px dotted gray;
            padding: 10px;

        }

        .nowrap {
            white-space: nowrap;
        }

        .cream_color {
            background-color: #fff6db;
        }

        .error-message {
            color: red;
        }

        .address-inputbox {
            border: none;
            font-weight: bold;

        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allottee Details test</title>
</head>

<body>

    <div class="container-fluid ">
        <div class="container">
            <form id="updateAllotteeDetailsForm" action="" method="post">
                <div class="row  my-3 cream_color">


                    <!-- inclue allotte sql script -->
                    <?php
                    //  SQL JOIN QUERY  
                    if (isset($_GET['partyId'])) {
                        $partyId = mysqli_real_escape_string($conn, $_GET['partyId']); // Sanitize the input
                        $sql = "SELECT p.PARTY_ID, pc.FIRST_NAME, pc.LAST_NAME, MAX(pcm.CONTACT_MECH_ID) AS CONTACT_MECH_ID,
                        MAX(tn.CONTACT_NUMBER) AS CONTACT_NUMBER,
                        MAX(tn.SECOND_CONTACT_NUMBER) AS SECOND_CONTACT_NUMBER,
                        MAX(pa.ADDRESS1) AS ADDRESS1,
                        MAX(pa.ADDRESS2) AS ADDRESS2,
                        MAX(ad.COAPPLICANT_NAME) AS COAPPLICANT_NAME,
                        MAX(ad.FLAT_UNIT_NUMBER) AS FLAT_UNIT_NUMBER,
                        MAX(CASE WHEN cm.CONTACT_MECH_TYPE_ID = 'EMAIL_ADDRESS' THEN cm.INFO_STRING
                           ELSE NULL
                           END) AS EXTRACTED_EMAIL,
                           dr.data_resource_id,
                           dr.OBJECT_INFO,
                           dr.DATA_RESOURCE_NAME,
                           c.content_id,
                           pcn.PARTY_CONTENT_TYPE_ID
                        FROM party_copy AS p
                        LEFT JOIN person_copy AS pc ON p.PARTY_ID = pc.PARTY_ID
                        LEFT JOIN party_contact_mech_copy AS pcm ON p.PARTY_ID = pcm.PARTY_ID
                        LEFT JOIN telecom_number_copy AS tn ON pcm.CONTACT_MECH_ID = tn.CONTACT_MECH_ID
                        LEFT JOIN postal_address_copy AS pa ON pcm.CONTACT_MECH_ID = pa.CONTACT_MECH_ID
                        LEFT JOIN appartment_details_copy AS ad ON p.PARTY_ID = ad.PARTY_ID
                        LEFT JOIN contact_mech_copy AS cm ON pcm.CONTACT_MECH_ID = cm.CONTACT_MECH_ID
                        LEFT JOIN party_content AS pcn ON p.PARTY_ID = pcn.party_id
                        LEFT JOIN content AS c ON pcn.content_id = c.content_id 
                        LEFT JOIN data_resource AS dr ON c.data_resource_id = dr.data_resource_id
                        WHERE p.PARTY_ID = '$partyId' AND p.PARTY_ID LIKE 'PID0000%'
                        GROUP BY p.PARTY_ID, pc.FIRST_NAME, pc.LAST_NAME, dr.data_resource_id, c.content_id, pcn.PARTY_CONTENT_TYPE_ID";


                        $result = mysqli_query($conn, $sql);

                        if ($result) {
                            // Fetch the first row from the result set
                            $row = mysqli_fetch_assoc($result);
                            // Extracting individual values from the fetched row
                            $firstName = $row['FIRST_NAME'];
                            $lastName = $row['LAST_NAME'];
                            $phoneNumber = $row['CONTACT_NUMBER'];
                            $secPhoneNumber = $row['SECOND_CONTACT_NUMBER'];
                            $emailAddress = $row['EXTRACTED_EMAIL'];
                            $flatUnitNo = $row['FLAT_UNIT_NUMBER'];
                            $coapplicantName = $row['COAPPLICANT_NAME'];


                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Invalid or missing 'partyId' parameter.";
                    }

                    ?>
                    <!-- Display allottee details in input field -->
                    <div>
                        <div class="col my-4">
                            <input type="text" class="form-control" placeholder="First name"
                                value="<?php echo $firstName; ?>" name="firstname" id="firstname">
                        </div>
                        <div class="col my-4">
                            <input type="text" class="form-control" placeholder="Last name"
                                value="<?php echo $lastName; ?>" name="lastname">
                        </div>
                        <div class="col my-4">
                            <input type="text" class="form-control" placeholder="Phone number"
                                value="<?php echo $phoneNumber; ?>" name="phonenumber" id="phonenumber">
                        </div>
                        <div class="col my-4">
                            <input type="text" class="form-control" placeholder="Secondary phone number"
                                value="<?php echo $secPhoneNumber; ?>" name="sec_phonenumber" id="sec_phonenumber">
                        </div>
                        <div class="col my-4">
                            <input type="text" class="form-control" placeholder="Email address"
                                value="<?php echo $emailAddress; ?>">
                        </div>
                    </div>

                    <div class="col-auto">
                        <h4 class="my-4">Address Details</h4>
                        <div class="dotted-border my-2">
                            <div class="font-weight-bold p-0">

                                <!-- Display allottee address details  -->
                                <?php echo $row['ADDRESS1']; ?><br>
                                <?php echo $row['ADDRESS2']; ?><br>

                            </div>
                        </div>
                        <div class="row col my-2">
                            <h6>Flat unit number</h6>
                        </div>

                        <div class="row col">
                            <input type="text" id="" class="form-control" placeholder="Flat unit number"
                                value="<?php echo $flatUnitNo; ?>" name="flatunitnumber" id="flatunitnumber">
                        </div>

                        <div class="row col my-2">
                            <h6>Coapplicant name</h6>
                        </div>
                        <div class="row col">
                            <input type="text" id="" class="form-control" placeholder="Coapplicant name"
                                value="<?php echo $coapplicantName; ?>" name="coapplicantname" id="coapplicantname">
                        </div>
                        <div class="row col my-2">
                            <button type="submit" class="btn btn-primary btn-sm ">Save details</button>
                        </div>
                    </div> <!--col-->


                </div><!-- cream-color div -->
            </form>






            <!-- Evidences row -->
            <div class="row ">
                <div class="col">
                    <h3>Evidences</h3>
                </div>
            </div>
            <?php

            echo '<form id="" action="" method="post" enctype="multipart/form-data">';
            $json = json_decode('[{"adharcard":{"name":"Aadhar card :","elementName":"adharupload"}},
            {"pancard":{"name":" Pan Card :","elementName":"pancardupload"}},
            {"allotment_letter":{"name":"Allotment Letter :","elementName":"allotmentletterupload"}},
            {"bba":{"name":"BBA :","elementName":"bbaupload"}},
            {"bank_receipt":{"name":"Bank Receipt :","elementName":"bankreceiptupload"}},
            {"payment_receipt":{"name":"Payment Receipt :","elementName":"paymentreceiptupload"}}]');

            // Loop through each item in the $json array
            foreach ($json as $document) {
                $documentName = key($document);
                $documentElementName = $document->$documentName->elementName;

                // Check if evidence exists for the current document in $partyEvidence array
                $evidenceType = strtolower($documentName);
                $evidenceStatus = isset($partyEvidence[$partyId]) ? $partyEvidence[$partyId][$evidenceType] === '✅' : false;
                ?>
                <!-- Start of a new row for each document -->
                <div class="row forEvidence cream_color">
                    <!-- Column for the document name -->
                    <div class="col-2 d-flex align-items-center">
                        <h6>
                            <?php echo $document->$documentName->name; ?>
                        </h6>
                    </div>

                    <!-- Column for the check and cross signs -->
                    <div class="col-1 d-flex align-items-center ">
                        <div class="chkFile <?php echo $evidenceType; ?>Status">

                        </div>
                    </div>

                    <div class="col-5 my-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="<?php echo $documentElementName; ?>"
                                name="<?php echo $documentElementName; ?>" onchange="displayFileName(this)">
                            <label class="custom-file-label" for="<?php echo $documentElementName; ?>">Choose file</label>
                        </div>
                    </div>


                    <!-- Column for buttons (Show and Upload) -->
                    <div class="col-md-4 my-2 nowrap">
                        <div class="col-md-4 my-2 nowrap">
                            <button type="button" class="btn btn-primary btn-sm show-docs-btn" data-toggle="modal"
                                data-target="#exampleModal" data-party-id="<?php echo $partyId; ?>"
                                data-evidence-type="<?php echo $evidenceType; ?>">Show
                            </button>

                            <button type="submit" class="btn btn-primary btn-sm ">Upload</button>

                            <button type="button" class="btn btn-danger btn-sm delete-docs-btn" data-toggle=""
                                data-target="" data-party-id="<?php echo $partyId; ?>"
                                data-evidence-type="<?php echo $evidenceType; ?>">Delete
                            </button>

                        </div>
                    </div>
                </div>
                <!-- End of the row for the current document -->
                <?php
            }
            echo '</form>';
            ?>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center" id="document-details-body">
                    <!-- Details will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Include the full version of jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="../jquery/validation.js"></script>
    <script>
        // Function to display the selected file name in the corresponding label
        function displayFileName(input) {
            // Get the file name from the input element
            var fileName = input.files[0].name;
            // Find the next sibling, which is assumed to be the label for the file input
            var label = input.nextElementSibling;
            // Set the innerHTML of the label to the file name, displaying it to the user
            label.innerHTML = fileName;
        }

        $(document).ready(function () {
            // for show documents
            $('.show-docs-btn').click(function () {
                var partyId = $(this).data('party-id');
                var evidenceType = $(this).data('evidence-type');

                $.ajax({
                    type: 'POST',
                    url: 'get_document_details2.php', // replace with your server-side script
                    data: { partyId: partyId, evidenceType: evidenceType },
                    success: function (response) {
                        $('#document-details-body').html(response);
                    }
                });
            });

            // for show document status
            var partyId = <?php echo json_encode($partyId); ?>;
            // Function to update document status
            function updateDocumentStatus(partyId, evidenceType) {
                $.ajax({
                    type: 'POST',
                    url: 'get_document_status2.php',
                    data: { partyId: partyId, evidenceType: evidenceType },
                    dataType: 'json',
                    success: function (statusResponse) {
                        // Check if there's an error in the response
                        if (statusResponse.error) {
                            console.error('Error:', statusResponse.error);
                            // You might want to display an error message in the modal
                        } else {
                            // Update the status in the corresponding chkFile div
                            $('.' + evidenceType + 'Status').text(statusResponse.documentStatus);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        console.error('AJAX Error:', error);
                    }
                });
            }


            // Loop through each item in the $json array
            <?php
            foreach ($json as $item) {
                foreach ($item as $key => $document) {
                    // Get the evidence type and call the function to update the status
                    $evidenceType = strtolower($key);
                    echo "updateDocumentStatus('$partyId', '$evidenceType');";
                }
            }
            ?>





            $('.delete-docs-btn').click(function () {
                // Read data attributes from the button
                var partyId = $(this).data('party-id');
                var evidenceType = $(this).data('evidence-type');

                // Make an AJAX request to delete the record
                $.ajax({
                    type: 'POST',
                    url: 'delete_Documents.php', // Replace with the actual file name
                    data: { partyId: partyId, evidenceType: evidenceType },
                    success: function (response) {
                        // Handle success
                        if (response.includes('Document already deleted')) {
                            // Document already deleted, show a message to the user
                            alert('Document already deleted!');
                        } else {
                            // Record deleted successfully, show a success message
                            alert('Record deleted successfully!');
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle error, show an error message
                        console.error('Error deleting record: ' + error);
                        console.log(partyId);
                        console.log(evidenceType);
                    }
                });
            });



            $("#updateAllotteeDetailsForm").submit(function (e) {
                e.preventDefault();

                // Retrieve partyId from the URL
                var urlParams = new URLSearchParams(window.location.search);
                var partyIdFromUrl = urlParams.get('partyId');

                // Serialize form data
                var formData = $(this).serialize();

                // Add partyId to the formData
                formData += '&partyId=' + encodeURIComponent(partyIdFromUrl);

                // Log data and URL
                console.log('formData:', formData);


                $.ajax({
                    type: "POST",
                    url: "update_allottee_details.php",
                    data: formData,
                    dataType: 'json', // Specify dataType as JSON
                    success: function (response) {
                        if (response.error) {
                            // Handle error
                            console.log("Error: " + response.error);
                        } else {
                            // Handle success
                            alert("Details updated successfully")
                            $("#resultContainer").html(response.message);
                            console.log("Success: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX Error: " + status + " - " + error);
                    }
                });
            });



        });
    </script>



</body>

</html>