<?php
// require 'insertData.php'; //IN this file a generic function for insert 
function insertContactInformation($conn, $partyId, $contactMechTypeId, $contactInfo, $currentDateTime)
{
    // Generate a unique contact mechanism ID
    $ContactMechId = generateUniqueId($conn, "CMID", "contact_mech");

    if ($ContactMechId !== false) {
        // Insert the contact information into the `contact_mech` table

        $dataToInsert = array(
            'CONTACT_MECH_ID' => $ContactMechId,
            'CONTACT_MECH_TYPE_ID' => $contactMechTypeId,
            'INFO_STRING' => $contactInfo,
        );
        insertData("contact_mech", $dataToInsert, $conn); // Takes arguments 'table_name','associative array','db connection'

        // Check if the insertData function was successful
        if (!mysqli_error($conn)) {
            // Insert a row in the `party_contact_mech` table to link the contact mech to the party
            // $insertPartyContactMechSql = "INSERT INTO `party_contact_mech` (`PARTY_ID`, `CONTACT_MECH_ID`) VALUES ('$partyId', '$ContactMechId')";
            $dataToInsert = array(
                'PARTY_ID' => $partyId,
                'CONTACT_MECH_ID' => $ContactMechId,
                'FROM_DATE' => $currentDateTime,

            );
            insertData("party_contact_mech", $dataToInsert, $conn); // Takes arguments 'table_name','associative array','db connection'




            if (!mysqli_error($conn)) {
                // Determine the purpose type based on the contactMechType
                $contactMechPurposeTypeId = ($contactMechTypeId === 'EMAIL_ADDRESS') ? 'PRIMARY_EMAIL' : ($contactMechTypeId === 'POSTAL_ADDRESS' ? 'GENERAL_LOCATION' : 'PRIMARY_PHONE');

                // Insert into `party_contact_mech_purpose`
                // $createPartyContactMechPurposeSql = "INSERT INTO `party_contact_mech_purpose` (`PARTY_ID`, `CONTACT_MECH_ID`, `CONTACT_MECH_PURPOSE_TYPE_ID`) VALUES ('$partyId', '$ContactMechId', '$contactMechPurposeTypeId')";
                // $resultPartyContactMechPurposeCreate = mysqli_query($conn, $createPartyContactMechPurposeSql);
                $dataToInsert = array(
                    'PARTY_ID' => $partyId,
                    'CONTACT_MECH_ID' => $ContactMechId,
                    'CONTACT_MECH_PURPOSE_TYPE_ID' => $contactMechPurposeTypeId,
                    'FROM_DATE' => $currentDateTime,
                );
                insertData("party_contact_mech_purpose", $dataToInsert, $conn); // Takes arguments 'table_name','associative array','db connection'

                if (!mysqli_error($conn)) {
                    return $ContactMechId; // Successfully inserted contact information
                } else {
                    return "Error inserting into party_contact_mech_purpose: " . mysqli_error($conn);
                }
            } else {
                return "Error inserting into party_contact_mech: " . mysqli_error($conn);
            }
        } else {
            return "Error inserting into contact_mech: " . mysqli_error($conn);
        }
    } else {
        return "Error generating a unique contact mechanism ID";
    }
}

?>