<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connect.php';
require 'generateDateTime.php';
require 'insertData.php';

function generateUniqueId($conn, $prefix, $tableName)
{
    // Specify the path to a lock file
// Specify the path to a lock file
$lockFilePath =  'Sync.lock';

    // Attempt to acquire an exclusive lock
    $lockFile = fopen($lockFilePath, 'w');

    if (flock($lockFile, LOCK_EX)) {
        // Inside the critical section (only one process can be here at a time)
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

            // Release the lock
            flock($lockFile, LOCK_UN);

            // Close the lock file
            fclose($lockFile);

            // Return the unique ID
            return $uniqueId;
        } else {
            // Handle the error if the SQL query fails

            // Release the lock in case of an error
            flock($lockFile, LOCK_UN);

            // Close the lock file
            fclose($lockFile);

            return false;
        }
    } else {
        // Unable to acquire lock, handle accordingly
        fclose($lockFile);
        return "Unable to generate ID at this time.";
    }
}

// Example usage
// $generatedId = generateUniqueId($conn, "TEST", "test_id");
// echo $generatedId;

// $insertTestId = array(
//     'PARTY_ID' => $generatedId,
//     // 'parallel' => 'parallel1',
// );

// // Assuming insertData is defined in insertData.php
// insertData("test_id", $insertTestId, $conn);




?>