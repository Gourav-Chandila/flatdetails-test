<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Forget Password</title>
</head>

<body>

    <?php
    // Check if the form was submitted
    if (isset($_POST['submit'])) {
        // Get user input from the form
        $phonenumber1 = $_POST['phonenumber'];
        $emailaddress1 = $_POST['emailaddress'];
        $newpassword1 = $_POST['newpassword'];

        require 'db_connect.php';
        // Check whether the email address and phone number exist
        if (!empty($emailaddress1) && !empty($phonenumber1)) {

            $existSql = "SELECT * FROM `person_data` WHERE email_address = '$emailaddress1' AND phone_number='$phonenumber1'";
            $result = mysqli_query($conn, $existSql);
            $numExistRows = mysqli_num_rows($result);

            if ($numExistRows > 0) {
                // Update the password for the user
                $hashedPassword = password_hash($newpassword1, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateSql = "UPDATE `person_data` SET password = '$hashedPassword' WHERE email_address = '$emailaddress1'";
                if (mysqli_query($conn, $updateSql)) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="success">
                                <strong>&#128522;</strong> Password reset successful.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
                } else {
                    echo "Error updating password: " . mysqli_error($conn);
                }
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>&#10071; </strong> Invalid email address or phone number. 
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

    <form action="forget_password.php" id="forget_password_page_Form" method="post">
        <!-- Your form fields with corrected names -->
        <div class="container-fluid main-div">
            <div class="container body-div">
                <div class="row mt-4">
                    <div class="col-12 col-div">
                        <form action="login_page.php" id="login_page_Form" method="post">
                            <?php
                            $json = json_decode('[{"username":{"name":"User name","elementName":"username","elementIdName":"username","inputType":"text"}},
                            {"phonenumber":{"name":"Phone number","elementName":"phonenumber","elementIdName":"phonenumber","inputType":"number"}},
                            {"new_password":{"name":"New pasword","elementName":"new_password","elementIdName":"new_newpassword","inputType":"password"}}]');
                            foreach ($json as $field) {
                                $formName = key($field);
                                $formData = current($field);
                                echo '<div class="form-group">';
                                echo '<label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                                echo '<input type="' . $formData->inputType . '" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';

                                echo '</div>';
                            }
                            ?>


                            <button type="submit" class="btn btn-primary" name="submit">Reset password</button>
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
</body>

</html>