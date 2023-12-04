<?php
function generateUniqueId($conn, $prefix, $tableName)
{
    $lockFilePath = __DIR__ . '/generateUniqueId.lock';

    $lockFile = fopen($lockFilePath, 'a'); // Open the file in append mode
    if ($lockFile === false) {
        // Handle the case where opening the lock file fails
        return false;
    }

    // Get the current process ID and timestamp
    $currentProcessID = getmypid();
    echo getmypid();
    $acquireTime = date('Y-m-d H:i:s');

    if (flock($lockFile, LOCK_EX)) {
        // Log process information when acquiring the lock
        fwrite($lockFile, "Acquired by Process ID: $currentProcessID at $acquireTime\n");

        // Execute the SQL query to count records
        $countSqlRec = "SELECT COUNT(*) AS record_count FROM $tableName";
        $resultCountSqlRec = mysqli_query($conn, $countSqlRec);

        if ($resultCountSqlRec) {
            $row = mysqli_fetch_assoc($resultCountSqlRec);
            $recordCount = $row['record_count'] + 1;
            $idStartingWith = str_pad($recordCount, 12, '0', STR_PAD_LEFT);
            $uniqueId = $prefix . $idStartingWith;

            // Release the lock
            flock($lockFile, LOCK_UN);

            // Log process information when releasing the lock
            $releaseTime = date('Y-m-d H:i:s');
            fwrite($lockFile, "Released by Process ID: $currentProcessID at $releaseTime\n");

            fclose($lockFile);

            return $uniqueId;
        } else {
            // Handle the error if the SQL query fails
            // Release the lock
            flock($lockFile, LOCK_UN);
            fclose($lockFile);

            return false;
        }
    } else {
        // Handle the case where obtaining the lock fails
        fclose($lockFile);
        return false;
    }
}

?>