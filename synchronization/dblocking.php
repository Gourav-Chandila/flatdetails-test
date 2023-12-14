<?php

function performCriticalSection()
{
    // Your critical section logic goes here
    echo "Entering critical section...\n";
    sleep(5); // Simulating some work inside the critical section
    echo "Exiting critical section.\n";
}

function acquireLock($lockFilePath)
{
    $lockFile = fopen($lockFilePath, 'w');
    if (flock($lockFile, LOCK_EX)) {
        return $lockFile;
    } else {
        fclose($lockFile);
        return false;
    }
}

function releaseLock($lockFile)
{
    flock($lockFile, LOCK_UN);
    fclose($lockFile);
}

function generateUniqueId($conn, $prefix, $tableName, $lockFile)
{
    // Attempt to acquire the lock
    if (acquireLock($lockFile)) {
        try {
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
        } finally {
            // Release the lock when done
            releaseLock($lockFile);
        }
    } else {
        echo "Unable to acquire lock for generating unique ID. Another process is in the critical section.\n";
        return false;
    }
}
?>
