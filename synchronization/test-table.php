<?php
require 'db_connect.php';
require 'insertData.php';

// Set the lock timeout to a reasonable value
$lockTimeout = 10;

// Attempt to acquire a database-level lock
$lockAcquired = false;

while (!$lockAcquired) {
    mysqli_begin_transaction($conn);

    // Attempt to acquire a lock
    $result = mysqli_query($conn, 'SELECT GET_LOCK("unique_id_lock", ' . $lockTimeout . ') AS lock_result');

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $lockAcquired = (int)$row['lock_result'] === 1;

        // Release the result set
        mysqli_free_result($result);
        
        if ($lockAcquired) {
            // Generate unique ID
            $uniqueId = generateUniqueId($conn, 'test', 'test_id');

            // Use the generated unique ID as needed
            echo "Generated Unique ID: $uniqueId";

            // Insert data
            $insertTestId = array(
                'party_id' => $uniqueId,
            );
            insertData("test_id", $insertTestId, $conn);

            // Release the lock
            mysqli_query($conn, 'DO RELEASE_LOCK("unique_id_lock")');
            
            // Commit the transaction
            mysqli_commit($conn);
        } else {
            // Rollback the transaction
            mysqli_rollback($conn);
        }
    } else {
        // Handle the case where the lock acquisition query fails
        // Rollback the transaction
        mysqli_rollback($conn);
    }

    // If the lock is not acquired, wait and retry after a short delay
    if (!$lockAcquired) {
        usleep(100000); // Sleep for 100 milliseconds (adjust as needed)
    }
}

// Function for generating a unique ID
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
