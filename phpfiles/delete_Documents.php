<?php
// Include database connection code or configuration
require 'db_connect.php';
session_start();

try {
    // Check if either party_id  is set in the session
    if (isset($_SESSION['party_id'])) {
        // Use the available party_id from the session
        $partyId = $_SESSION['party_id'];

        $evidenceType = $_POST['evidenceType'];

        // Update the thru_date to the current date
        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('Y-m-d H:i:s');

        // Sanitize input (you may need more thorough validation)
        $partyId = mysqli_real_escape_string($conn, $partyId);
        $evidenceType = mysqli_real_escape_string($conn, $evidenceType);
        $currentDateTime = mysqli_real_escape_string($conn, $currentDateTime);

        // Debug output to check input values
        // echo "partyId: $partyId, evidenceType: $evidenceType, currentDateTime: $currentDateTime<br>";

        // Check if the record already has a THRU_DATE set
        $checkSql = "SELECT THRU_DATE FROM party_content WHERE PARTY_ID = '$partyId' AND PARTY_CONTENT_TYPE_ID = '$evidenceType'";
        $checkResult = mysqli_query($conn, $checkSql);

        if ($checkResult) {
            $allDeleted = true; // Assume all records are deleted

            while ($row = mysqli_fetch_assoc($checkResult)) {
                $thruDate = $row['THRU_DATE'];

                // If any record has a NULL or empty THRU_DATE, set $allDeleted to false
                if (empty($thruDate)) {
                    $allDeleted = false;
                    break;
                }
            }

            if ($allDeleted) {
                // All records already deleted, send response
                $htmlResponse = '<p>Document already deleted!</p>';
                echo $htmlResponse;
                exit; // Exit the script
            }
        } else {
            // Handle query error if needed
            error_log('Error: ' . mysqli_error($conn));
            echo '<p>Error checking database.</p>';
        }

        // Construct the update query
        $updateSql = "UPDATE party_content SET THRU_DATE = '$currentDateTime' WHERE PARTY_ID = '$partyId' AND PARTY_CONTENT_TYPE_ID = '$evidenceType' AND THRU_DATE IS NULL";

        // Execute the update query
        $updateResult = mysqli_query($conn, $updateSql);

        // Check for MySQL errors
        if (!$updateResult) {
            // Output MySQL error details for debugging
            error_log('Error: ' . mysqli_error($conn));
            echo '<p>Error updating database.</p>';
        }

        // Check for update success
        if ($updateResult) {
            $htmlResponse = '<p>Record deleted successfully!</p>';
        } else {
            // Handle update error if needed
            $htmlResponse = '<p>Error deleting record from the database.</p>';
        }

        // Send the HTML response back to the AJAX call
        echo $htmlResponse;
    } else {
        // Handle the case when  session variables are not set
        echo '<p>Error: Invalid or missing session variables.</p>';
    }
} catch (Exception $e) {
    // Handle exceptions
    error_log('Error: ' . $e->getMessage());
} finally {
    // Close the database connection
    mysqli_close($conn);
}
?>