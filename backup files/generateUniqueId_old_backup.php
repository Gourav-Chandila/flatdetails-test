

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
?>