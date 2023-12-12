<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connecting to the Database
    require 'phpfiles/db_connect.php';

    $phonenumber = $_POST["phonenumber"];
    $password = $_POST["password"];

    $sql = "SELECT pcm.PARTY_ID, pcm.CONTACT_MECH_ID, tn.CONTACT_NUMBER, ul.CURRENT_PASSWORD
            FROM party_contact_mech pcm
            JOIN telecom_number tn ON pcm.contact_mech_id = tn.contact_mech_id
            JOIN user_login ul ON ul.PARTY_ID = pcm.PARTY_ID
            WHERE tn.contact_number = '$phonenumber'";

    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Corrected column name to match the actual database schema
            if (password_verify($password, $row['CURRENT_PASSWORD'])) {
                // Set session variable
                session_start();
                $_SESSION['loggedin'] = true;
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Login successfully .
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>';
                // Use JavaScript to redirect to Display_data.php after 3 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'phpfiles/allotteeDetails.php?partyId=" . $row['PARTY_ID'] . "';
                    }, 2000); // 3 seconds
                </script>";
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>&#10071;</strong>Invalid credintials.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>';
            }
        }
    } else {


    }

    // Close the database connection
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
    <!-- <link rel="stylesheet" type="text/css" href="css/style.css"> -->
    <title>Login page</title>
</head>

<body>

    <?php require 'phpfiles/navbar.php' ?>
    <div class="container my-5 align-items-center">
        <form action="index.php" id="login_page_Form" method="post">
            <?php
            $json = json_decode('[{"phonenumber":{"name":"Phone number","elementName":"phonenumber","elementIdName":"phonenumber","inputType":""}},
                              {"password":{"name":"Pasword","elementName":"password","elementIdName":"password","inputType":"password"}}]');

            foreach ($json as $field) {
                $formName = key($field);
                $formData = current($field);

                echo '    <div class="col-md-7">';
                echo '        <div class="form-group">';
                echo '            <label for="' . $formData->elementName . '">' . $formData->name . '</label>';
                // Check if the current field is a password field
                if ($formData->inputType === "password") {
                    echo '            <div class="input-group">';
                    echo '                <div class="input-group-append">';
                    echo '                    <button class="btn btn-outline-success" type="button" onclick="togglePasswordVisibility(\'' . $formData->elementIdName . '\')">Show</button>';
                    echo '                </div>';
                    echo '                <input type="password" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                    echo '            </div>';
                } else {
                    echo '            <input type="' . $formData->inputType . '" name="' . $formData->elementName . '" class="form-control custom-input" id="' . $formData->elementIdName . '">';
                }
                echo '        </div>';
                echo '    </div>';
            }

            ?>





            <div class="container">
                <div class="row col">
                    <div class="form-group">
                        <a href="phpfiles/forget_password.php">Forget password ?</a>
                        <a href="phpfiles/Register_page.php">Register </a>
                    </div>
                </div>
                <div class="row col">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="js/validation.js"></script> -->
    <!-- Show password on button click -->
    <script src="js/show_password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>



</body>

</html>