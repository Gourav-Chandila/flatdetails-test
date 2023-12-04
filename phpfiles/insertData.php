<?php

// Include or require your database connection file
require_once 'db_connect.php';

function getColumnNames($table, $conn)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM $table");
    $columns = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

function insertData($table, $data, $conn)
{
    date_default_timezone_set('Asia/Kolkata');
    $currentTimeStamp = date('Y-m-d H:i:s');

    // Get column names in the table
    $tableColumns = getColumnNames($table, $conn);

    // Check if the columns exist
    $includeCreatedStamp = in_array('CREATED_STAMP', $tableColumns);
    $includeLastUpdatedStamp = in_array('LAST_UPDATED_STAMP', $tableColumns);
    // echo "Include created_stamp: " . $includeCreatedStamp . "<br>";
    // echo "Include last_updated_stamp: " . $includeLastUpdatedStamp . "<br>";

    // Build the SQL query
    $fields = implode(", ", array_keys($data));
    $values = "'" . implode("', '", $data) . "'";

    // Include timestamps if they exist in the table
    if ($includeCreatedStamp && $includeLastUpdatedStamp) {
        $fields .= ", created_stamp, last_updated_stamp";
        $values .= ", '$currentTimeStamp', '$currentTimeStamp'";
    } elseif ($includeLastUpdatedStamp) {
        $fields .= ", last_updated_stamp";
        $values .= ", '$currentTimeStamp'";
    } elseif ($includeCreatedStamp) {
        $fields .= ", created_stamp";
        $values .= ", '$currentTimeStamp'";
    }

    $sql = "INSERT INTO $table ($fields) VALUES ($values)";

    if (mysqli_query($conn, $sql)) {

    } else {
        error_log("There are some problem in inserting data " . mysqli_error($conn));
    }
}



// Example usage
// $dataToInsert = array(
//     'F_NAME' => 'testName',
//     'LAST_NAME' => 'testLname',
//     'id' => '13',
// );
// insertData("chktable", $dataToInsert, $conn);

?>