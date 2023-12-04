<?php
// Include your database connection file
require 'db_connect.php';

// Check if the necessary parameters are present in the POST request
if (isset($_POST['partyId']) && isset($_POST['evidenceType'])) {
    $partyId = mysqli_real_escape_string($conn, $_POST['partyId']);
    $evidenceType = mysqli_real_escape_string($conn, $_POST['evidenceType']);

    // Query to get the document status for the specified party_id and evidenceType
    $sql = "SELECT party_id, content_id
            FROM party_content
            WHERE party_id = '$partyId' AND PARTY_CONTENT_TYPE_ID = '$evidenceType'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $documentStatus = ($result->num_rows > 0) ? '✔️' : '❌';

        // Return the party_id, evidenceType, and documentStatus as JSON
        header('Content-Type: application/json');
        echo json_encode(['party_id' => $partyId, 'evidenceType' => $evidenceType, 'documentStatus' => $documentStatus]);
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