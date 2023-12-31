<?php
require 'generateDateTime.php';
require 'HandlingExceptions.php';
require 'insertData.php';

function handleDatabaseException($message)
{

    // Display a generic error message on the screen
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071; There are some technical issues at this time. Please try again later. </strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>';
}

function handleForeignKeyConstraintException($message)
{
    error_log('Foreign Key Constraint Exception: ' . $message, 3, 'error.log');
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071;</strong> ' . $message . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>';
}

try {
    $currentDateTime = getCurrentTimestamp();
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get user input from the form
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phonenumber = $_POST['phonenumber'];
        $sec_phonenumber = $_POST['sec_phonenumber'];
        $emailaddress = $_POST['emailaddress'];
        $coapplicantname = $_POST['coapplicantname'];
        $flatunitnumber = $_POST['flatunitnumber'];
        $address1 = $_POST['address1'];
        $address2 = $_POST['address2'];
        $password = $_POST['password'];

        // Include the database connection file
        require 'db_connect.php';

        // Start a transaction
        mysqli_begin_transaction($conn);

        // Check whether the phone number already exists in the database
        $existSql_phoneno = "SELECT CONTACT_MECH_ID FROM `telecom_number` WHERE CONTACT_NUMBER = '$phonenumber'";
        $result_phonenumber = mysqli_query($conn, $existSql_phoneno);

        if (mysqli_num_rows($result_phonenumber) > 0) {
            // Display an alert if the phone number already exists
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071;</strong> Phone number already exists.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        </div>';

            echo '  <div class="alert alert-primary" role="alert">A simple primary alert—check it out!</div>';

        } else {
            // Generate a unique Party ID for the user
            require 'generateUniqueId.php';
            $uniquePID1 = generateUniqueId($conn, "PID", "party");
            echo $uniquePID1;
            // Insert user data into the 'party' table
            $partyTypeId = "PERSON";
            $insertPartySql = array(
                'PARTY_ID' => $uniquePID1,
                'PARTY_TYPE_ID' => $partyTypeId,

            );
            insertData("party", $insertPartySql, $conn);


            // Insert user data into the 'user_login' table
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Generate hash password
            $userLoginId = $phonenumber;
            $insertUserLoginSql = array(
                'USER_LOGIN_ID' => $userLoginId,
                'PARTY_ID' => $uniquePID1,
                'CURRENT_PASSWORD' => $hashedPassword,

            );
            insertData("user_login", $insertUserLoginSql, $conn);

            // Insert user data into the 'person_copy' table
            $insertPersonDetailsSql = array(
                'PARTY_ID' => $uniquePID1,
                'FIRST_NAME' => $firstname,
                'LAST_NAME' => $lastname,
            );
            insertData("person", $insertPersonDetailsSql, $conn);

            require 'insertContactDetails.php'; // Include the file for contact details
            if ($insertPartySql) {
                // Create an associative array to store contact mechanisms
                $contactMech = array(
                    'TELECOM_NUMBER' => 'NULL',
                    'EMAIL_ADDRESS' => $emailaddress,
                    'POSTAL_ADDRESS' => 'NULL'
                );

                // Loop through the contact mechanisms and insert them
                foreach ($contactMech as $contactMechType => $contactInfo) {
                    if (!empty($contactInfo)) {
                        $uniqueContactMechId = insertContactInformation($conn, $uniquePID1, $contactMechType, $contactInfo, $currentDateTime);

                        if ($uniqueContactMechId !== false) {
                            echo $contactMechType . " Contact Mech ID: " . $uniqueContactMechId;

                            if ($contactMechType === 'TELECOM_NUMBER') {
                                $insertTelecomNumberSql = array(
                                    'CONTACT_MECH_ID' => $uniqueContactMechId,
                                    'CONTACT_NUMBER' => $phonenumber,
                                    'SECOND_CONTACT_NUMBER' => $sec_phonenumber,

                                );
                                insertData("telecom_number", $insertTelecomNumberSql, $conn);

                                if (!$insertTelecomNumberSql) {
                                    throw new DatabaseException("Error inserting phone number contact information.");
                                }
                            } elseif ($contactMechType === 'POSTAL_ADDRESS') {
                                $insertPostalAddressSql = array(
                                    'CONTACT_MECH_ID' => $uniqueContactMechId,
                                    'ADDRESS1' => $address1,
                                    'ADDRESS2' => $address2,
                                );
                                insertData("postal_address", $insertPostalAddressSql, $conn);
                                if (!$insertPostalAddressSql) {
                                    throw new DatabaseException("Error inserting postal address information.");
                                }
                            }
                        }
                    }
                }
                // Insert Apartment Information
                require 'insertAppartmentDetails.php';
                if ($insertPartySql && $insertPersonDetailsSql && $insertAppartmentDetailsSql) {
                    // Set session variable for logged-in user
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['party_id'] = $uniquePID1;
                    $_SESSION['user_login_id'] = $userLoginId;
                    $_SESSION['first_name'] = $firstname;
                    $_SESSION['last_name'] = $lastname;
                    // error_log('Party id from Register_page : ' . $_SESSION['party_id'], 0);
                    // error_log('User login id from Register_page : ' . $_SESSION['user_login_id'], 0);
                    // error_log('First name from Register_page : ' . $_SESSION['first_name'], 0);
                    // error_log('Last name from Register_page : ' . $_SESSION['last_name'], 0);

                    // Display a success message if the user data is inserted successfully
                    echo '<div class="alert alert-success alert-dismissible fade show" role="success" id="myAlert">
                <strong>&#128522;</strong> Registered successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">×</span>
                 </button>
                </div>';
                    echo '  <div class="alert alert-primary" role="alert">A simple primary alert—check it out!</div>';
                    //Handling Documents Upload
                    require 'uploadDocuments.php';
                    header("Location: http://localhost/sysnomy/flatdetails-test/phpfiles/allotteeDetails.php");
                    // Use JavaScript to redirect to Display_data.php after 3 seconds
                    // echo "<script>
                    //     setTimeout(function() {
                    //         window.location.href = 'http://localhost/sysnomy/flatdetails-test/phpfiles/allotteeDetails.php?partyId=$uniquePID1"';
                    //     }, 3000); // 3 seconds
                    // </script>";

                    mysqli_commit($conn);
                    exit();
                    // Commit the transaction if everything is successful
                } else {

                }

                // Close the database connection
                mysqli_close($conn);
            }
        }
    }
} catch (DatabaseException $e) {
    mysqli_rollback($conn);
    handleDatabaseException($e->getMessage());
} catch (ForeignKeyConstraintException $e) {
    mysqli_rollback($conn);
    handleForeignKeyConstraintException($e->getMessage());
    // error_log("[$currentDateTime] Foreign Key Constraint Exception: " . $e->getMessage() . PHP_EOL, 3, 'error.log');
    error_log("[$currentDateTime] Foreign Key Constraint Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . PHP_EOL, 3, 'error.log');


} catch (Exception $e) {
    mysqli_rollback($conn);
    // Get the current timestamp
    $timestamp = date("Y-m-d H:i:s");
    // Log the detailed error message with timestamp in the error log file
    // error_log("[$currentDateTime] General Exception: " . $e->getMessage() . PHP_EOL, 3, 'error.log');
    error_log("[$currentDateTime] General Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . PHP_EOL, 3, 'error.log');
    // Call the function to handle the error on the screen
    handleDatabaseException($e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Register User Details</title>

</head>

<body>


    <?php require 'navbar.php'; ?>
    <div class="container-fluid">

        <div class="container">

            <form id="index2MyForm" action="Register_page.php" method="post" enctype="multipart/form-data">
                <!-- Handling Form Submission -->
                <div class="row">
                    <!-- Left Side (col-6) -->
                    <div class="col-md-5 pink my-4">
                        <?php
                        $jsonLeft = json_decode('[{"firstname":{"name":"First Name *","elementName":"firstname","elementIdName":"firstname"}},
                         {"lastname":{"name":"Last name","elementName":"lastname","elementIdName":"lastname"}},
                         {"coapplicant_name":{"name":"Coapplicant Name","elementName":"coapplicantname","elementIdName":"coapplicantname"}},
                         {"sec_phonenumber":{"name":"Secondary phone number","elementName":"sec_phonenumber","elementIdName":"sec_phonenumber"}},
                         {"address1":{"name":"Address1","elementName":"address1","elementIdName":"address1"}},
                         {"address2":{"name":"Address2","elementName":"address2","elementIdName":"address2"}},
                         {"flatunitnumber":{"name":"Flat unit number","elementName":"flatunitnumber","elementIdName":"flatunitnumber"}}]');

                        foreach ($jsonLeft as $field) {
                            $formName = key($field);
                            $formData = current($field);
                            echo '    <div class="form-group">';
                            echo '        <label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                            echo '        <input type="text" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                            echo '    </div>';
                        }
                        ?>
                    </div>
                    <!-- Right Side (col-6) -->
                    <div class="col-md-7 green my-4">
                        <?php
                        $jsonRight = json_decode('[{"emailaddress":{"name":"Email address","elementName":"emailaddress","elementIdName":"emailaddress","inputType":"text"}},
                         {"phonenumber":{"name":"Phone number *","elementName":"phonenumber","elementIdName":"phonenumber","inputType":""}},
                         {"password":{"name":"Password","elementName":"password","elementIdName":"password","inputType":"password"}},
                         {"c_password":{"name":"Confirm password","elementName":"c_password","elementIdName":"c_password","inputType":"password"}}]');

                        foreach ($jsonRight as $field) {
                            $formName = key($field);
                            $formData = current($field);

                            echo '<div class="col-md-6">';
                            echo '    <div class="form-group">';
                            echo '        <label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                            // Check if the current field is a password field
                            if ($formData->inputType === "password") {
                                echo '        <div class="input-group">';
                                echo '            <div class="input-group-append">';
                                echo '                <button class="btn btn-outline-success" type="button" onclick="togglePasswordVisibility(\'' . $formData->elementIdName . '\')">Show</button>';
                                echo '            </div>';
                                echo '            <input type="password" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                                echo '        </div>';
                            } else {
                                echo '        <input type="' . $formData->inputType . '" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                            }
                            // Add a container for error messages
                            echo '        <div class="error-container" id="' . $formData->elementIdName . '-error"></div>';
                            echo '    </div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <button type="submit" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1">Register</button>

                    <button type="button" class="btn btn-primary btn success ml-3 mt-2 px-3 py-1" data-toggle="modal"
                        data-target="#exampleModal">Upload Documents <sup>Optional</sup></button>
                </div>

                <!-- Modal  -->
                <div class=" m-3">
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Documents</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="fileUploadForm" enctype="multipart/form-data">
                                        <!-- Handling Document Upload-->

                                        <?php
                                        $json = json_decode('[{"adharupload":{"name":"Aadhar card :","elementName":"adharupload"}},
                                                        {"pancard":{"name":" Pan Card :","elementName":"pancardupload"}},
                                                        {"allotmentletter":{"name":"Allotment Letter :","elementName":"allotmentletterupload"}},
                                                        {"bba":{"name":"BBA :","elementName":"bbaupload"}},
                                                        {"bankreceipt":{"name":"Bank Receipt :","elementName":"bankreceiptupload"}},
                                                        {"paymentreceipt":{"name":"Payment Receipt :","elementName":"paymentreceiptupload"}}]');

                                        foreach ($json as $document) {
                                            $documentName = key($document);
                                            $documentElementName = $document->$documentName->elementName;

                                            // Column for the choose file 
                                            echo '<div class="row my-3">';
                                            echo '  <div class="col-3 doc_title_no_wrap">';
                                            echo '    ' . $document->$documentName->name;
                                            echo '  </div>';
                                            echo '  <div class="col-9 ">';
                                            echo '      <div class="custom-file">';
                                            echo '          <input type="file" class="custom-file-input" id="' . $documentElementName . '" name="' . $documentElementName . '" onchange="displayFileName(this)">';
                                            echo '          <label class="custom-file-label" for="' . $documentElementName . '">Choose file</label>';
                                            echo '      </div>';
                                            echo '  </div>';
                                            echo '</div>';
                                        }
                                        ?>


                                    </form>

                                </div>
                                <div class="modal-footer form-group">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit" name="upload">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- </modal> -->
            </form>

        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="../js/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
    <script src="../js/show_password.js"></script>
    <script>
        // Function to display the selected file name in the custom-file-label in upload document button
        function displayFileName(input) {
            // Get the file name from the input element
            var fileName = input.files[0].name;
            // Find the next sibling, which is assumed to be the label for the file input
            var label = input.nextElementSibling;
            // Set the innerHTML of the label to the file name, displaying it to the user
            label.innerHTML = fileName;
        }// function end
    </script>
</body>

</html>