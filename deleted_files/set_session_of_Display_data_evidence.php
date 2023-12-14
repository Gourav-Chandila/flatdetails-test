<?php
// set_session.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['party_id'])) {
        // Set the party_id in the session
        $_SESSION['selected_party_id'] = $_POST['party_id'];
        // error_log('Party_id from Display_data_evidence : ' . $_SESSION['selected_party_id'], 0);
        echo 'Session variable set successfully.';
        echo 'Party_id from Display_data_evidence : ' . $_SESSION['selected_party_id'];
    } else {
        echo 'Failed to set session variable. Party ID not provided.';
    }
} else {
    echo 'Invalid request method.';
}
?>
