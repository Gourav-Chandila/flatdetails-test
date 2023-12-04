
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




    // Submit form using AJAX when the updateAllotteeDetailsForm is submitted
    $("#updateAllotteeDetailsForm").submit(function (e) {
        // Prevent the default form submission
        e.preventDefault();
        // Retrieve partyId from the URL
        var urlParams = new URLSearchParams(window.location.search);
        var partyIdFromUrl = urlParams.get('partyId');
        // Serialize form data
        var formData = $(this).serialize();
        // Add partyId to the formData
        formData += '&partyId=' + encodeURIComponent(partyIdFromUrl);
        // Log data and URL for debugging purposes
        console.log('formData:', formData);

        // Make an AJAX request to 'update_allottee_details'
        $.ajax({
            type: "POST",
            url: "update_allottee_details.php",
            data: formData,
            dataType: 'json', // Specify dataType as JSON
            success: function (response) {
                if (response.includes('update')) {
                    // console.log("Error: " + response.error);
                    $("#updateDetails-error-msg").html(`
                   <div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>working on it .<strong>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true"></span>
                  </button>
                 </div> `);
                } else {
                    // Handle success if the server returns a success response
                    // alert("Details updated successfully");
                    $("#updateDetails-error-msg").html(`
                   <div class="alert alert-success alert-dismissible fade show" role="alert">
                   <strong>Your details updated successfully.</strong>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true"></span>
                  </button>
                 </div> `);
                    $("#resultContainer").html(response.message);
                    console.log("Success: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                // console.log("AJAX Error: " + status + " - " + error);
                $("#updateDetails-error-msg").html(`
                   <div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>Oops! Something went wrong while updating your details. Please try again later.</strong>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                   <span aria-hidden="true"></span>
                  </button>
                 </div> `);
            }
        });
    });



});
