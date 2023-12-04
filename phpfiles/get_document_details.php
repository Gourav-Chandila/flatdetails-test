<?php
// Using AJAX
// Include database connection 
require 'db_connect.php';

// Get data from AJAX request (sanitize and validate if needed)
$partyId = $_POST['partyId'];
$evidenceType = $_POST['evidenceType'];

// Sanitize and validate input 
$partyId = mysqli_real_escape_string($conn, $partyId);
$evidenceType = mysqli_real_escape_string($conn, $evidenceType);

// Check if there are records with thru_date set to null
$checkDocument = "SELECT COUNT(*) AS recordCount FROM party_content WHERE party_id = '$partyId' AND party_content_type_id = '$evidenceType' AND thru_date IS NULL";
$checkDocumentResult = mysqli_query($conn, $checkDocument);

// Check for query success
if ($checkDocumentResult) {
    $row = mysqli_fetch_assoc($checkDocumentResult);
    $recordCount = $row['recordCount'];

    if ($recordCount > 0) {
        // Retrieve the latest record with 'thru_date' set to null for a specific party and evidence type.
        $chkEvidenceNull = "SELECT dr.object_info, dr.data_resource_name, pc.from_date
            FROM data_resource dr
            JOIN content c ON dr.data_resource_id = c.data_resource_id
            JOIN party_content pc ON c.content_id = pc.content_id
            WHERE pc.party_id = '$partyId' AND pc.party_content_type_id = '$evidenceType' AND pc.thru_date IS NULL
            ORDER BY pc.from_date DESC
            LIMIT 1";

        $resultEvidenceNull = mysqli_query($conn, $chkEvidenceNull);

        // Check for query success
        if ($resultEvidenceNull) {
            if ($row = mysqli_fetch_assoc($resultEvidenceNull)) {
                // Data found
                $objectInfo = $row['object_info'];
                $dataResourceName = $row['data_resource_name'];
                $fromDate = $row['from_date'];

                // Check if object_info contains "phpfiles/uploads/"
                if (strpos($objectInfo, 'phpfiles/uploads/') === 0) {
                    // Remove "phpfiles/"
                    $objectInfo = substr($objectInfo, strlen('phpfiles/'));
                }

                // Extract file extension from data_resource_name
                $fileExtension = pathinfo($dataResourceName, PATHINFO_EXTENSION);

                // Display image if file extension is jpg, jpeg, or png
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    $htmlResponse = '<div class="document-container"><img src="' . $objectInfo . '" alt="Document Image" class="document-image"></div>';
                }
                // Display PDF if file extension is pdf
                elseif ($fileExtension === 'pdf') {
                    $htmlResponse = '<div class="document-container"><iframe src="' . $objectInfo . '" type="application/pdf" class="document-pdf"></iframe></div>';
                  

                }

                // Display the last uploaded date
                // $htmlResponse .= '<p>Last Uploaded Date: ' . $fromDate . '</p>';
            }
        } else {
            // Handle query error if needed
            $htmlResponse = '<p>Error retrieving data from the database.</p>';
        }
    } else {
        // No records with thru_date set to null, show "No Data Found"
        $htmlResponse = '<p>No Data Found</p>';
    }
} else {
    // Handle query error if needed
    $htmlResponse = '<p>Error checking thru_date from the database.</p>';
}

// Send the HTML response back to the AJAX call
echo $htmlResponse;
?>