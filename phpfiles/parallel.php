<?php
// require 'db_connect.php';
// require 'generateDateTime.php';
require 'Sync.php'; // Make sure this line is correct

$uniqueIds = [];
$uniqueIdSync = generateUniqueId($conn, "TEST", "test_id");

$insertTestId = array(
    'PARTY_ID' => $uniqueIdSync,
    // 'parallel' => 'parallel1',
);

// Assuming insertData is defined in insertData.php
insertData("test_id", $insertTestId, $conn);
?>
