<?php

require 'db_connect.php';
require 'generateDateTime.php';
header('Content-Type: application/json');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['party_id'])) {
        try {
            // Start a transaction
            mysqli_begin_transaction($conn);
            $currentDateTime = getCurrentTimestamp();
            // Use array_map to apply mysqli_real_escape_string to each element in $_POST
            $escapedPostData = array_map(function ($value) use ($conn) {
                return mysqli_real_escape_string($conn, $value);
            }, $_POST);
            // Extract individual variables from the sanitized array  
            // $partyIdFromUrl = $escapedPostData['partyId']; // Assuming partyId is present in $_POST
            $firstName = $escapedPostData['firstname']; // Assuming first name is present in $_POST
            $lastName = $escapedPostData['lastname'];
            $coapplicantName = $escapedPostData['coapplicantname'];
            $flatUnitNo = $escapedPostData['flatunitnumber'];
            $phonenumber = $escapedPostData['phonenumber'];
            $sec_phonenumber = $escapedPostData['sec_phonenumber'];
            $address1 = $escapedPostData['address1'];
            $address2 = $escapedPostData['address2'];
            $emailAddress = $escapedPostData['primaryemail'];

            // $partyIdFrmSession = $_SESSION['party_id'];
            $partyIdFrmSession = $_SESSION['party_id'];
            $updateAllotteeDetails = "
                UPDATE person AS p
                JOIN appartment_details AS ad ON p.PARTY_ID = ad.PARTY_ID
                JOIN telecom_number AS tn ON tn.contact_mech_id = (
                    SELECT pcm.contact_mech_id
                    FROM party_contact_mech pcm
                    WHERE pcm.contact_mech_id = tn.contact_mech_id AND pcm.party_id = '$partyIdFrmSession'
                )
                JOIN postal_address AS pa ON pa.contact_mech_id = (
                    SELECT pcm.contact_mech_id
                    FROM party_contact_mech pcm
                    WHERE pcm.contact_mech_id = pa.contact_mech_id AND pcm.party_id = '$partyIdFrmSession'
                )
                JOIN contact_mech ON contact_mech.contact_mech_id = (
                    SELECT pcm.contact_mech_id
                    FROM party_contact_mech pcm
                    WHERE pcm.contact_mech_id = contact_mech.contact_mech_id AND pcm.party_id = '$partyIdFrmSession'
                )
                JOIN party_contact_mech pcm ON contact_mech.contact_mech_id = pcm.contact_mech_id
                SET
                    p.FIRST_NAME = '$firstName',
                    p.LAST_NAME = '$lastName',
                    p.LAST_UPDATED_STAMP='$currentDateTime',

                    ad.COAPPLICANT_NAME = '$coapplicantName',
                    ad.FLAT_UNIT_NUMBER = '$flatUnitNo',
                    ad.LAST_UPDATED_STAMP='$currentDateTime',

                    tn.CONTACT_NUMBER = '$phonenumber',
                    tn.SECOND_CONTACT_NUMBER = '$sec_phonenumber',
                    tn.LAST_UPDATED_STAMP='$currentDateTime',

                    pa.ADDRESS1 = '$address1',
                    pa.ADDRESS2 = '$address2',
                    pa.LAST_UPDATED_STAMP='$currentDateTime',

                    contact_mech.info_string = CASE
                        WHEN contact_mech.contact_mech_type_id = 'EMAIL_ADDRESS' THEN '$emailAddress'
                        ELSE NULL
                    END
                WHERE p.PARTY_ID = '$partyIdFrmSession';
            ";

            $Htmlresponse = [];

            if (!mysqli_multi_query($conn, $updateAllotteeDetails)) {
                // Rollback the transaction on error
                mysqli_rollback($conn);
                error_log('Error: ' . mysqli_error($conn));
            } else {
                // Check the number of affected rows
                $affectedRows = mysqli_affected_rows($conn);

                if ($affectedRows > 0) {
                    // Commit the transaction on success
                    mysqli_commit($conn);
                    $response['success'] = 'All details updated successfully';
                } else {
                    // Rollback the transaction if no rows were affected
                    mysqli_rollback($conn);
                    $response['error'] = 'Your details already updated.';
                }
            }
        } catch (Exception $e) {
            // Log the detailed error for internal use
            // Rollback the transaction on error
            mysqli_rollback($conn);
            error_log('Error: ' . $e->getMessage());
        } finally {
            // Echo the JSON-encoded response array
            echo json_encode($response);
            mysqli_close($conn);
        }
    } else {
        // If 'partyId' is missing, echo a JSON-encoded error message
        echo json_encode(['error' => 'Invalid or missing "partyId" parameter.']);
    }
} else {
    // If the request method is not POST, echo a JSON-encoded error message
    echo json_encode(['error' => 'Invalid request method.']);
}
?>