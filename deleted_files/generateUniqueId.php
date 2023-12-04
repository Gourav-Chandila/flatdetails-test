<?php
// Generate a unique user_id for user_details table
function generateUniqueUserID($conn)
{
    $prefix = "UID";
    $countSqlRecords = "SELECT COUNT(user_id) AS record_count FROM user_details";
    // Execute the SQL query to count records
    $result = mysqli_query($conn, $countSqlRecords);
    if ($result) {
        // Fetch the count from the result
        $row = mysqli_fetch_assoc($result);
        $recordCount = $row['record_count'] + 1;
        // Generate a 8-digit number by padding the record count
        $idStartingWith = str_pad($recordCount, 8, '0', STR_PAD_LEFT);
        // Concatenate "UID" with the 4-digit number
        $uniqueID = $prefix . $idStartingWith;
        // Return the unique ID
        return $uniqueID;
    } else {
        // Handle the error if the SQL query fails
        return false;
    }
}

// Generate a unique DATA_RESOURCE_ID for data_resource table
// function generateUniquedataResId($conn)
// {
//     $prefix = "DATA";
//     // $countSqlRecords = "SELECT COUNT(*) AS record_count FROM document_resource";
//     $countSqlRecords = "SELECT COUNT(*) AS record_count FROM data_resource";
//     // Execute the SQL query to count records
//     $result = mysqli_query($conn, $countSqlRecords);
//     if ($result) {
//         // Fetch the count from the result
//         $row = mysqli_fetch_assoc($result);
//         $recordCount = $row['record_count'] + 1;
//         // Generate a 8-digit number by padding the record count
//         $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
//         // Concatenate "DOC_ID" with the 12-digit number
//         $uniqueID = $prefix . $idStartingWith;
//         // Return the unique ID
//         return $uniqueID;
//     } else {
//         // Handle the error if the SQL query fails
//         return false;
//     }
// }


// This function generates a unique Party ID for a new record in a database table.
// function generateUniquePartyId($conn)
// {
//     // Define a prefix for the Party ID
//     $prefix = "PID";
//     // SQL query to count the number of records in the 'party' table
//     $countSqlRecords = "SELECT COUNT(PARTY_ID) AS record_count FROM party_copy";
//     // Execute the SQL query to count records
//     $result = mysqli_query($conn, $countSqlRecords);

//     if ($result) {
//         // Fetch the count from the result
//         $row = mysqli_fetch_assoc($result);
//         $recordCount = $row['record_count'] + 1;
//         // Generate an 8-digit number by padding the record count with leading zeros
//         $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
//         // Concatenate the prefix "PID" with the 8-digit number
//         $uniquePID = $prefix . $idStartingWith;
//         // Return the unique ID
//         return $uniquePID;
//     } else {
//         // Handle the error if the SQL query fails
//         return false;
//     }
// }

// This function generates a unique Contact_mech_id for a new record in a database table.
// function generateUniqueContactMechId($conn)
// {
//     // Define a prefix for the Party ID
//     $prefix = "CMID";
//     // SQL query to count the number of records in the 'party' table
//     $countSqlRecords = "SELECT COUNT(CONTACT_MECH_ID) AS record_count FROM contact_mech_copy";
//     // Execute the SQL query to count records
//     $result = mysqli_query($conn, $countSqlRecords);

//     if ($result) {
//         // Fetch the count from the result
//         $row = mysqli_fetch_assoc($result);
//         $recordCount = $row['record_count'] + 1;
//         // Generate an 6-digit number by padding the record count with leading zeros
//         $idStartingWith = str_pad($recordCount, 8, '0', STR_PAD_LEFT);
//         // Concatenate the prefix "CMID" with the 6-digit number
//         $uniqueContactMechId = $prefix . $idStartingWith;
//         // Return the unique ID
//         return $uniqueContactMechId;
//     } else {
//         // Handle the error if the SQL query fails
//         return false;
//     }

// }

// // This function generates a unique Appartment_id for a new record in a database table.
// function generateAppartmentId($conn)
// {
//     // Define a prefix for the Party ID
//     $prefix = "APTID";
//     // SQL query to count the number of records in the 'APPARTMENT_DETAILS' table
//     $countSqlRecords = "SELECT COUNT(APPARTMENT_ID) AS record_count FROM appartment_details_copy";
//     // Execute the SQL query to count records
//     $result = mysqli_query($conn, $countSqlRecords);

//     if ($result) {
//         // Fetch the count from the result
//         $row = mysqli_fetch_assoc($result);
//         $recordCount = $row['record_count'] + 1;
//         // Generate an 6-digit number by padding the record count with leading zeros
//         $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
//         // Concatenate the prefix "CMID" with the 12-digit number
//         $uniqueAppartmentId = $prefix . $idStartingWith;
//         // Return the unique ID
//         return $uniqueAppartmentId;
//     } else {
//         // Handle the error if the SQL query fails
//         return false;
//     }
// }


// This function generates a unique content_id for a new record in a database table.
// function generateContentId($conn)
// {
//     // Define a prefix for the CONTENT ID
//     $prefix = "CONTID";
//     // SQL query to count the number of records in the 'CONTENT' table
//     $countSqlRecords = "SELECT COUNT(CONTENT_ID) AS record_count FROM content";
//     // Execute the SQL query to count records
//     $result = mysqli_query($conn, $countSqlRecords);

//     if ($result) {
//         // Fetch the count from the result
//         $row = mysqli_fetch_assoc($result);
//         $recordCount = $row['record_count'] + 1;
//         // Generate an 6-digit number by padding the record count with leading zeros
//         $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
//         // Concatenate the prefix "CONID" with the 12-digit number
//         $uniqueContentId = $prefix . $idStartingWith;
//         // Return the unique ID
//         return $uniqueContentId;
//     } else {
//         // Handle the error if the SQL query fails
//         return false;
//     }
// }



function generateUniqueId($conn, $prefix, $tableName)
{
    // Execute the SQL query to count records
    $countSqlRec = "SELECT COUNT(*) AS record_count FROM $tableName";
    $result = mysqli_query($conn, $countSqlRec);
    if ($result) {
        // Fetch the count from the result
        $row = mysqli_fetch_assoc($result);
        $recordCount = $row['record_count'] + 1;
        // Generate a 12-digit number by padding the record count with leading zeros
        $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
        // Concatenate the prefix with the 12-digit number
        $uniqueContentId = $prefix . $idStartingWith;
        // Return the unique ID
        return $uniqueContentId;
    } else {
        // Handle the error if the SQL query fails
        return false;
    }
}


?>