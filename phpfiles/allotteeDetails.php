<?php
error_reporting(0);
// Start the session
session_start();
error_log('Party id is : ' . $_SESSION['party_id'], 0);
// Include necessary files
require 'generateUniqueId.php';
require 'insertData.php';
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if "partyId" is set in the session 
    if (isset($_SESSION['party_id'])) {
        $uniquePID1 = $_SESSION['party_id'];
        // Include necessary files
        require 'generateDateTime.php';
        require 'uploadDocuments.php';
    } else {
        error_log('Error getting user document details because "party_id" in session is not fetching in allotteeDetails page', 0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

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

    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allottee Details</title>
</head>

<body>
    <?php require 'navbar.php' ?>
    <div class="container-fluid ">
        <div class="container">
            <div class="updateDetails-error-msg" id="updateDetails-error-msg"></div>
            <form id="updateAllotteeDetailsForm" action="" method="post">
                <div class="row  my-3 cream_color">
                    <!-- inclue allotte sql script -->
                    <?php
                    //  SQL JOIN QUERY  
                    if (isset($_SESSION['party_id'])) {
                        $partyId = $_SESSION['party_id'];//getting party_id from session
                        $retrieveAlloteeDetails = "SELECT p.PARTY_ID, pc.FIRST_NAME, pc.LAST_NAME, MAX(pcm.CONTACT_MECH_ID) AS CONTACT_MECH_ID,
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
                        FROM party AS p
                        LEFT JOIN person AS pc ON p.PARTY_ID = pc.PARTY_ID
                        LEFT JOIN party_contact_mech AS pcm ON p.PARTY_ID = pcm.PARTY_ID
                        LEFT JOIN telecom_number AS tn ON pcm.CONTACT_MECH_ID = tn.CONTACT_MECH_ID
                        LEFT JOIN postal_address AS pa ON pcm.CONTACT_MECH_ID = pa.CONTACT_MECH_ID
                        LEFT JOIN appartment_details AS ad ON p.PARTY_ID = ad.PARTY_ID
                        LEFT JOIN contact_mech AS cm ON pcm.CONTACT_MECH_ID = cm.CONTACT_MECH_ID
                        LEFT JOIN party_content AS pcn ON p.PARTY_ID = pcn.party_id
                        LEFT JOIN content AS c ON pcn.content_id = c.content_id 
                        LEFT JOIN data_resource AS dr ON c.data_resource_id = dr.data_resource_id
                        WHERE p.PARTY_ID = '$partyId' AND p.PARTY_ID LIKE 'PID0000%'
                        GROUP BY p.PARTY_ID, pc.FIRST_NAME, pc.LAST_NAME, dr.data_resource_id, c.content_id, pcn.PARTY_CONTENT_TYPE_ID";


                        $resultRetrieveAlloteeDetails = mysqli_query($conn, $retrieveAlloteeDetails);

                        if ($resultRetrieveAlloteeDetails) {
                            // Fetch the first row from the result set
                            $row = mysqli_fetch_assoc($resultRetrieveAlloteeDetails);
                            // Extracting individual values from the fetched row
                            $firstName = $row['FIRST_NAME'];
                            $_SESSION['first_name'] = $row['FIRST_NAME'];
                            $lastName = $row['LAST_NAME'];
                            $_SESSION['last_name'] = $row['LAST_NAME'];
                            $phoneNumber = $row['CONTACT_NUMBER'];
                            $secPhoneNumber = $row['SECOND_CONTACT_NUMBER'];
                            $emailAddress = $row['EXTRACTED_EMAIL'];
                            $flatUnitNo = $row['FLAT_UNIT_NUMBER'];
                            $coapplicantName = $row['COAPPLICANT_NAME'];
                            $address1 = $row['ADDRESS1'];
                            $address2 = $row['ADDRESS2'];
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        error_log("Error getting user details because 'party_id' in session is not fetching in allotteeDetails page.", 0);
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
                                value="<?php echo $emailAddress; ?>" name="primaryemail" id="primaryemail">
                        </div>
                    </div>

                    <div class="col-auto">
                        <h4 class="my-4">Address Details</h4>
                        <div class="dotted-border my-2 ">
                            <!-- Address1 input field -->
                            <input type="text" id="" class="form-control border-0 font-weight-bold w-auto "
                                placeholder="Address1" value="<?php echo $address1; ?>" name="address1" id="address1"
                                oninput="autoSizeInput(this)">
                            <!-- Address2 input field -->
                            <input type="text" id="" class="form-control border-0 font-weight-bold w-auto "
                                placeholder="Address2" value="<?php echo $address2; ?>" name="address2" id="address2"
                                oninput="autoSizeInput(this)">

                            <script>
                                function autoSizeInput(input) {
                                    input.style.width = (input.value.length + 15) * 8 + 'px'; // Changing size of address input field
                                }
                            </script>



                        </div>
                        <div class="row col ">
                            <h6>Flat unit number</h6>
                        </div>

                        <div class="row col">
                            <input type="text" id="" class="form-control " placeholder="Flat unit number"
                                value="<?php echo $flatUnitNo; ?>" name="flatunitnumber" id="flatunitnumber">
                        </div>

                        <div class="row col ">
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




            <!-- show message related document delete -->
            <div class="deleteDoc-error-msg" id="deleteDoc-error-msg"></div>
            <!-- Evidences row -->
            <div class="row ">
                <div class="col">
                    <h3>Evidences</h3>
                </div>
            </div>
            <?php

            echo '<form id="" action="" method="post" enctype="multipart/form-data">';
            $json = json_decode('[{"ADHARCARD":{"name":"Aadhar card :","elementName":"adharupload"}},
            {"PANCARD":{"name":" Pan Card :","elementName":"pancardupload"}},
            {"ALLOTMENT_LETTER":{"name":"Allotment Letter :","elementName":"allotmentletterupload"}},
            {"BBA":{"name":"BBA :","elementName":"bbaupload"}},
            {"BANK_RECEIPT":{"name":"Bank Receipt :","elementName":"bankreceiptupload"}},
            {"PAYMENT_RECEIPT":{"name":"Payment Receipt :","elementName":"paymentreceiptupload"}}]');

            // Loop through each item in the $json array
            foreach ($json as $document) {
                $documentName = key($document);
                $documentElementName = $document->$documentName->elementName;

                // Check if evidence exists for the current document in $partyEvidence array
                $evidenceType = $documentName;
                // $evidenceStatus = isset($partyEvidence[$partyId]) ? $partyEvidence[$partyId][$evidenceType] === '✅' : false;
                ?>
                <!-- Start of a new row for each document -->
                <div class="row forEvidence cream_color">
                    <!-- Column for the document name -->
                    <div class="col-md-1 col-sm-2 col-12 d-flex align-items-center">
                        <h6>
                            <?php echo $document->$documentName->name; ?>
                        </h6>
                    </div>

                    <!-- Column for the check and cross signs -->
                    <div class="col-md-1 col-sm-2 col-12 d-flex align-items-center ">
                        <div class="chkFile <?php echo $evidenceType; ?>Status">

                        </div>
                    </div>
                    <!-- Column for the choose file -->
                    <div class="col-md-3 col-sm-6 col-12 my-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="<?php echo $documentElementName; ?>"
                                name="<?php echo $documentElementName; ?>" onchange="displayFileName(this)">
                            <label class="custom-file-label" for="<?php echo $documentElementName; ?>">Choose file</label>
                        </div>
                    </div>

                    <!-- Column for buttons (Show,Upload and delete) -->
                    <div class="col-md-4 col-sm-12 col-12 my-2 nowrap">
                        <div class="col-md-12 col-12 my-2 nowrap">
                            <button type="button" class="btn btn-primary btn-sm show-docs-btn" data-toggle="modal"
                                data-target="#showDocumentModal" data-party-id="<?php echo $partyId; ?>"
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
    <div class="modal fade" id="showDocumentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center " id="document-details-body">
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

    <script src="../js/validation.js"></script>
    <!-- <script src="../js/ajax_request.js"></script> do in sta and sun -->

    <script>
        // Function to display the selected file name in the custom-file-label label
        function displayFileName(input) {
            // Get the file name from the input element
            var fileName = input.files[0].name;
            // Find the next sibling, which is assumed to be the label for the file input
            var label = input.nextElementSibling;
            // Set the innerHTML of the label to the file name, displaying it to the user
            label.innerHTML = fileName;
        }// function end



        // Executes the provided function when the document structure is fully loaded and ready for manipulation.
        $(document).ready(function () {
            // Triggered when the 'show-docs-btn' button is clicked.
            $('.show-docs-btn').click(function () {
                var partyId = $(this).data('party-id');
                var evidenceType = $(this).data('evidence-type');
                // Make an AJAX request to retrive document data
                $.ajax({
                    type: 'POST',
                    url: 'get_document_details.php', // file where ajax request hit
                    data: { partyId: partyId, evidenceType: evidenceType },
                    success: function (response) {
                        $('#document-details-body').html(response);
                        // console.log(partyId);
                        // console.log("Success getting  " + evidenceType + "  data" );
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        // console.error('AJAX Error:', error);
                        console.error('Error getting  document data: ' + error);
                        console.log(partyId);
                        console.log(evidenceType);
                    }
                });
            });


            // var partyId = <?php echo json_encode($partyId); ?>;
            // Function to get document status
            function getDocumentStatus(partyId, evidenceType) {
                // Make an AJAX request to get document status 
                $.ajax({
                    type: 'POST',
                    url: 'get_document_status.php',
                    data: { partyId: partyId, evidenceType: evidenceType },
                    dataType: 'json',
                    success: function (statusResponse) {
                        // get the document status in the corresponding chkFile div
                        $('.' + evidenceType + 'Status').text(statusResponse.documentStatus);
                    },
                    error: function (xhr, status, error) {
                        // console.error('AJAX Error:', error);
                        console.error('Error getting  document status: ' + error);
                        console.log(partyId);
                        console.log(evidenceType);
                    }
                });
            }
            // Loop through each item in the $json array
            <?php
            foreach ($json as $item) {
                foreach ($item as $key => $document) {
                    // Get the evidence type and call the function to get the status
                    echo "getDocumentStatus('$partyId', '$key');";
                }
            }
            ?>




            // Triggered when the 'delete-docs-btn' button is clicked.
            $('.delete-docs-btn').click(function () {
                // Cache the reference to $(this) to avoid repeated jQuery object creation
                var $this = $(this);
                // Read data attributes from the button
                var partyId = $this.data('party-id');
                var evidenceType = $this.data('evidence-type');

                // Make an AJAX request to delete the document
                $.ajax({
                    type: 'POST',
                    url: 'delete_Documents.php',
                    data: { partyId: partyId, evidenceType: evidenceType },
                    success: function (response) {
                        // Handle success
                        if (response.includes('Document already deleted')) {
                            // Document already deleted, show a message to the user
                            // alert('Document already deleted!');
                            $("#deleteDoc-error-msg").html(`
                           <div class="alert alert-danger alert-dismissible fade show" role="alert">
                           <strong>${evidenceType} is already deleted .<strong>
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true"></span>
                          </button>
                         </div> `);
                        } else {
                            // Document deleted successfully, show a success message
                            // alert('Document deleted successfully!');
                            $("#deleteDoc-error-msg").html(`
                           <div class="alert alert-success alert-dismissible fade show" role="alert">
                           <strong>${evidenceType} deleted Successfully</strong>
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true"></span>
                          </button>
                         </div> `);

                            getDocumentStatus(partyId, evidenceType);//when document is deleted than it get document status and show in chkfile div
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle error, show an error message
                        console.error('Error deleting document: ' + error);
                        console.log(partyId);
                        console.log(evidenceType);
                    }
                });
            });




            // Variable to store the last submitted form data
            var lastSubmittedFormData = null;

            $("#updateAllotteeDetailsForm").submit(function (e) {
                e.preventDefault();

                // Serialize the current form data
                var currentFormData = $(this).serialize();

                // Check if the current form data is the same as the last submitted form data
                if (currentFormData === lastSubmittedFormData) {
                    // Show "already updated" message
                    $("#updateDetails-error-msg").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Your details are already updated.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
             </div>
             `);

                    return; // Exit the function and prevent further processing
                }
                // If the form data has changed, update lastSubmittedFormData
                lastSubmittedFormData = currentFormData;
                var urlParams = new URLSearchParams(window.location.search);
                var partyIdFromUrl = urlParams.get('partyId');
                var formData = currentFormData + '&partyId=' + encodeURIComponent(partyIdFromUrl);

                console.log('formData:', formData);

                $.ajax({
                    type: "POST",
                    url: "update_allottee_details.php",
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.error) {
                            // Handle other errors

                        } else {
                            // Handle success if the server returns a success response
                            $("#updateDetails-error-msg").html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Your details updated successfully.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                `);
                            $("#resultContainer").html(response.message);
                            console.log("Success: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#updateDetails-error-msg").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops! Something went wrong while updating your details. Please try again later.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
            `);
                    }
                });
            });

        });
    </script>



</body>

</html>