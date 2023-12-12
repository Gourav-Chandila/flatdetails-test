<?php
//Generic method for generate uniqueId 
function generateUniqueId($conn, $prefix, $tableName)
{
    // Execute the SQL query to count records
    $countSqlRec = "SELECT COUNT(*) AS record_count FROM $tableName";
    $resultCountSqlRec = mysqli_query($conn, $countSqlRec);
    if ($resultCountSqlRec) {
        // Fetch the count from the result
        $row = mysqli_fetch_assoc($resultCountSqlRec);
        $recordCount = $row['record_count'] + 1;
        // Generate a 12-digit number by padding the record count with leading zeros
        $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
        // Concatenate the prefix with the 12-digit number
        $uniqueId = $prefix . $idStartingWith;
        // Return the unique ID
        return $uniqueId;
    } else {
        // Handle the error if the SQL query fails
        return false;
    }
}

?>