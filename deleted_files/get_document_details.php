<?php
// Using AJAX
// Include database connection code or configuration
require 'db_connect.php';

// Get data from AJAX request (sanitize and validate if needed)
$partyId = $_POST['partyId'];
$evidenceType = $_POST['evidenceType'];

// Sanitize and validate input (adjust based on your needs)
$partyId = mysqli_real_escape_string($conn, $partyId);
$evidenceType = mysqli_real_escape_string($conn, $evidenceType);

// Query the database to get the relevant details with the latest date
$sql = "SELECT dr.object_info, dr.data_resource_name, pc.from_date
        FROM data_resource dr
        JOIN content c ON dr.data_resource_id = c.data_resource_id
        JOIN party_content pc ON c.content_id = pc.content_id
        WHERE pc.party_id = '$partyId' AND pc.party_content_type_id = '$evidenceType'
        ORDER BY pc.from_date DESC
        LIMIT 1";

$result = mysqli_query($conn, $sql);

// Check for query success
if ($result) {
    if ($row = mysqli_fetch_assoc($result)) {
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
            $htmlResponse = '<img src="' . $objectInfo . '" alt="Document Image" class="Document_image">';
        }
        // Display PDF if file extension is pdf
        elseif ($fileExtension === 'pdf') {
            $htmlResponse = '<embed src="' . $objectInfo . '" type="application/pdf" width="100%" height="400px" />';
        }

        // Display the last uploaded date
        // $htmlResponse .= '<p>Last Uploaded Date: ' . $fromDate . '</p>';
    } else {
        // No data found
        $htmlResponse = '<p>Data Not Found</p>';
    }
} else {
    // Handle query error if needed
    $htmlResponse = '<p>Error retrieving data from the database.</p>';
}

// Send the HTML response back to the AJAX call
echo $htmlResponse;
?>
