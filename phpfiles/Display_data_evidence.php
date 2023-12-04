<?php
// Include your database connection code
require 'db_connect.php';


$sql = "SELECT
    p.PARTY_ID,
    pc.FIRST_NAME,
    pc.LAST_NAME,
    MAX(pcm.CONTACT_MECH_ID) AS CONTACT_MECH_ID,
    MAX(tn.CONTACT_NUMBER) AS CONTACT_NUMBER,
    MAX(tn.SECOND_CONTACT_NUMBER) AS SECOND_CONTACT_NUMBER,
    MAX(pa.ADDRESS1) AS ADDRESS1,
    MAX(pa.ADDRESS2) AS ADDRESS2,
    MAX(ad.COAPPLICANT_NAME) AS COAPPLICANT_NAME,
    MAX(ad.FLAT_UNIT_NUMBER) AS FLAT_UNIT_NUMBER,
    MAX(CASE
        WHEN cm.CONTACT_MECH_TYPE_ID = 'EMAIL_ADDRESS' THEN cm.INFO_STRING
        ELSE NULL
        END) AS EXTRACTED_EMAIL,
    dr.data_resource_id,
    c.content_id,
    pcn.PARTY_CONTENT_TYPE_ID,
    pcn.thru_date
FROM party_copy AS p
LEFT JOIN person_copy AS pc ON p.PARTY_ID = pc.PARTY_ID
LEFT JOIN party_contact_mech_copy AS pcm ON p.PARTY_ID = pcm.PARTY_ID
LEFT JOIN telecom_number_copy AS tn ON pcm.CONTACT_MECH_ID = tn.CONTACT_MECH_ID
LEFT JOIN postal_address_copy AS pa ON pcm.CONTACT_MECH_ID = pa.CONTACT_MECH_ID
LEFT JOIN appartment_details_copy AS ad ON p.PARTY_ID = ad.PARTY_ID
LEFT JOIN contact_mech_copy AS cm ON pcm.CONTACT_MECH_ID = cm.CONTACT_MECH_ID
LEFT JOIN party_content AS pcn ON p.PARTY_ID = pcn.party_id
LEFT JOIN content AS c ON pcn.content_id = c.content_id
LEFT JOIN data_resource AS dr ON c.data_resource_id = dr.data_resource_id
WHERE p.PARTY_ID LIKE 'PID0000%'
GROUP BY p.PARTY_ID, pc.FIRST_NAME, pc.LAST_NAME, dr.data_resource_id, c.content_id, pcn.PARTY_CONTENT_TYPE_ID";


// Execute the SQL query
$result = $conn->query($sql);

if ($result === false) {
    // Handle the database query error, e.g., log it or display an error message.
    die("Database query error: " . $conn->error);
}

$partyEvidence = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $partyId = $row['PARTY_ID'];

        if (!isset($partyEvidence[$partyId])) {
            // Create an array to store party information
            $partyEvidence[$partyId] = array(
                'party_id' => $row['PARTY_ID'],
                'First_name' => $row['FIRST_NAME'],
                'Last_name' => $row['LAST_NAME'],
                'Phone number' => $row['CONTACT_NUMBER'],
                'Second Phone number' => $row['SECOND_CONTACT_NUMBER'] ? $row['SECOND_CONTACT_NUMBER'] : 'N/A',
                'Email address' => $row['EXTRACTED_EMAIL'] ? $row['EXTRACTED_EMAIL'] : 'N/A',
                'Address1' => $row['ADDRESS1'],
                'Address2' => $row['ADDRESS2'],
                'Coapplican name' => $row['COAPPLICANT_NAME'] ? $row['COAPPLICANT_NAME'] : 'N/A',
                'Flat unit number' => $row['FLAT_UNIT_NUMBER'],
                'Evidence' => 'adharcard:❌, pancard:❌, allotment_letter:❌, bba:❌, bank_receipt:❌, payment_receipt:❌ ',
            );
        }

        // Extract the evidence type directly from the row data
        $evidenceType = strtolower($row['PARTY_CONTENT_TYPE_ID']);
        if ($evidenceType && !empty($row['content_id'])) {
            // Update evidence status for the specific type
            $evidenceStatus = !is_null($row['thru_date']) ? '❌' : '✅';
            $partyEvidence[$partyId]['Evidence'] = str_replace("$evidenceType:❌", "$evidenceType:$evidenceStatus", $partyEvidence[$partyId]['Evidence']);
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Display All Allottees</title>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <table id="myTable" class="display">
        <thead>
            <tr>
                <!-- Insert headers in table -->
                <th>Party ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Second Phone Number</th>
                <th>Email address</th>
                <th>Address1</th>
                <th>Address2</th>
                <th>Coapplicant Name</th>
                <th>Flat Unit Number</th>
                <th>Evidence</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //itrate through loop and insert values in columns
            foreach ($partyEvidence as $party) {

                echo "<tr>";

                echo "<td>{$party['party_id']}</td>";
                // echo "<td>{$party['First_name']}</td>";
                echo "<td><a href='allotteeDetails.php?partyId=" . $party['party_id'] . "'>" . $party['First_name'] . "</a></td>";

                echo "<td>{$party['Last_name']}</td>";
                echo "<td>{$party['Phone number']}</td>";
                echo "<td>{$party['Second Phone number']}</td>";
                echo "<td>{$party['Email address']}</td>";
                echo "<td>{$party['Address1']}</td>";
                echo "<td>{$party['Address2']}</td>";
                echo "<td>{$party['Coapplican name']}</td>";
                echo "<td>{$party['Flat unit number']}</td>";
                echo "<td>{$party['Evidence']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- JavaScript dependencies (jQuery, Bootstrap, and DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>