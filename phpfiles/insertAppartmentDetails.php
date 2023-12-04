<?php
try {
    $AppartmentId1 = generateUniqueId($conn, "APTID", "appartment_details");

    // Using only the generic insert function
      $insertAppartmentDetailsSql = array(
        'APPARTMENT_ID' => $AppartmentId1,
        'PARTY_ID' => $uniquePID1,
        'COAPPLICANT_NAME' => $coapplicantname,
        'FLAT_UNIT_NUMBER' => $flatunitnumber,
    );
    insertData("appartment_details", $insertAppartmentDetailsSql, $conn); // Takes arguments 'table_name','associative array','db connection' than insert record in table 

} catch (DatabaseException $e) {
    // Log the error to the screen and a log file
    echo $e->errorMessage();
    error_log("Application Error: " . $e->errorMessage(), 3, "error.log");
} catch (ForeignKeyConstraintException | DuplicateEntryException | InvalidDataException $e) {
    // Log the error to the screen and a log file
    echo $e->errorMessage();
    error_log("Application Error: " . $e->errorMessage(), 3, "error.log");
}
?>