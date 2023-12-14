<?php
// Include your database connection file
require 'db_connect.php';
session_start();
// echo $_SESSION['party_id'];
// Check if the necessary parameters are present in the POST request
if (isset($_SESSION['party_id']) && isset($_POST['evidenceType'])) {

    // Determine the party_id based on the available session variable
    $partyId = $_SESSION['party_id'];
    $evidenceType = mysqli_real_escape_string($conn, $_POST['evidenceType']);
    // error_log("Evidence Type is : " . $evidenceType);

    // Query to check if there are records with thru_date set to null
    $checkSql = "SELECT COUNT(*) AS recordCount
                 FROM party_content
                 WHERE party_id = '$partyId' AND PARTY_CONTENT_TYPE_ID = '$evidenceType' AND thru_date IS  NULL";

    $checkResult = mysqli_query($conn, $checkSql);

    if ($checkResult) {
        $row = mysqli_fetch_assoc($checkResult);
        $recordCount = $row['recordCount'];

        // Return the party_id, evidenceType, and documentStatus as JSON
        header('Content-Type: application/json');
        echo json_encode(['party_id' => $partyId, 'evidenceType' => $evidenceType, 'documentStatus' => ($recordCount > 0) ? '✔️' : '❌']);
    } else {
        // Return an error message if the query fails
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: ' . mysqli_error($conn)]);
    }
} else {
    // Return an error message if parameters are missing
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid or missing parameters']);
}

// Close the database connection
mysqli_close($conn);
?>