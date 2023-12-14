<?php
if (isset($_POST['submit'])) {
    // Get user input from the form
    $phonenumber = $_POST['phonenumber'];
    $newpassword = $_POST['newpassword'];

    require 'db_connect.php';

    // Check whether the email address and phone number exist
    if (!empty($phonenumber) && !empty($newpassword)) {

        $existSql = "SELECT pcm.PARTY_ID, pcm.CONTACT_MECH_ID, tn.CONTACT_NUMBER, ul.CURRENT_PASSWORD
            FROM party_contact_mech pcm
            JOIN telecom_number tn ON pcm.contact_mech_id = tn.contact_mech_id
            JOIN user_login ul ON ul.PARTY_ID = pcm.PARTY_ID
            WHERE tn.contact_number = '$phonenumber'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);

        if ($numExistRows > 0) {
            // Update the password for the user
            $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateSql = "UPDATE user_login ul
                    JOIN party_contact_mech pcm ON ul.PARTY_ID = pcm.PARTY_ID
                    JOIN telecom_number tn ON pcm.contact_mech_id = tn.contact_mech_id
                    SET ul.CURRENT_PASSWORD = '$hashedPassword'
                    WHERE tn.contact_number = '$phonenumber'";
            if (mysqli_query($conn, $updateSql)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="success">
                                <strong>&#128522;</strong> Password reset successful.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
                               // Use JavaScript to redirect to Display_data.php after 3 seconds
                    echo '<script>
                        setTimeout(function() {
                            window.location.href = "/sysnomy/flatdetails-test/index.php";
                        }, 2000); // 3 seconds
                    </script>';

            } else {
                echo "Error updating password: " . mysqli_error($conn);
            }
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>&#10071; </strong> Invalid phone number. 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>';

        }
    } else {
        echo '<div class=" alert alert-warning alert-dismissible fade show " role="alert">
            <strong>&#10071; </strong> Please fill in both email address and phone number fields. 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';
    }

    mysqli_close($conn);
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- <link rel="stylesheet" type="text/css" href="../css/style.css"> -->
    <title>Forget Password</title>
</head>

<body>



    <form action="forget_password.php" id="forget_password_page_Form" method="post">
        <!-- Your form fields with corrected names -->
        <div class="container-fluid main-div">
            <div class="container body-div">
                <div class="row mt-4">
                    <div class="col-12 col-div">
                        <form action="forget_passworde.php" id="login_page_Form" method="post">
                            <?php
                            $json = json_decode('[{"phonenumber":{"name":"Phone number","elementName":"phonenumber","elementIdName":"phonenumber","inputType":""}},
                            {"newpassword":{"name":"New pasword","elementName":"newpassword","elementIdName":"newpassword","inputType":"password"}}]');
                            foreach ($json as $field) {
                                $formName = key($field);
                                $formData = current($field);

                                echo '    <div class="col-md-7 p-0">';
                                echo '        <div class="form-group">';
                                echo '            <label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                                // Check if the current field is a password field
                                if ($formData->inputType === "password") {
                                    echo '            <div class="input-group">';
                                    echo '                <div class="input-group-append">';
                                    echo '                    <button class="btn btn-outline-success" type="button" onclick="togglePasswordVisibility(\'' . $formData->elementIdName . '\')">Show</button>';
                                    echo '                </div>';
                                    echo '                <input type="password" name="' . $formData->elementName . '" class="form-control custom-input m-0" id="' . $formData->elementIdName . '">';
                                    echo '            </div>';
                                } else {
                                    echo '            <input type="' . $formData->inputType . '" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                                }
                                echo '        </div>';
                                echo '    </div>';
                            }
                            ?>
                            <div class="row col">
                                <button type="submit" class="btn btn-primary" name="submit">Reset password</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- <script src="../js/validation.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
    <!-- Show password on button click -->
    <script src="../js/show_password.js"></script>
</body>

</html>