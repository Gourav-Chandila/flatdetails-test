<?php
// require 'Sync.php'; // Assuming Sync.php contains the necessary functions
require 'dblocking.php'; // Assuming Sync.php contains the necessary functions

// Get the thread name from the JMeter variable
// $threadName = $_GET['threadName'];
$threadName = isset($_GET['threadName']) ? $_GET['threadName'] : 'defaultThread';
echo "Thread Name:".$threadName;

// Check if the thread name is valid
if($threadName !== 'defaultThread' && $threadName !== '') {
    // Generate a unique ID
    $uniqueIdSync = generateUniqueId($conn, "TEST", "test_id");

    // Insert data into the database
    $insertData = array(
        'PARTY_ID' => $uniqueIdSync,
        'THREAD_NAME' => "thread: ". $threadName, // Store the JMeter thread name in the database
    );

    // Assuming insertData is defined in Sync.php
    insertData("test_id", $insertData, $conn);

    // Optionally, return a response to JMeter
    echo 'Data inserted successfully!';
} else {
    // Optionally, return a response to JMeter indicating that data was not inserted
    echo 'Data not inserted. Invalid thread name.';
}
?>