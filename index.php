<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connecting to the Database
    require 'phpfiles/db_connect.php';
    $phonenumber = $_POST["phonenumber"];
    $password = $_POST["password"];
    //check details in db exist or not 
    $sql = "SELECT tn.CONTACT_NUMBER, ul.PARTY_ID, ul.CURRENT_PASSWORD, ul.USER_LOGIN_ID
               FROM user_login ul
               JOIN telecom_number tn ON ul.USER_LOGIN_ID = tn.CONTACT_NUMBER
               WHERE ul.USER_LOGIN_ID = '$phonenumber'";

    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Corrected column name to match the actual database schema
            if (password_verify($password, $row['CURRENT_PASSWORD'])) {
                // Set session variable
                session_start();
                $_SESSION['party_id'] = $row['PARTY_ID'];
                $_SESSION['user_login_id'] = $row['USER_LOGIN_ID'];
                // error_log('Party id from index : ' . $_SESSION['party_id'], 0);
                // error_log('User login id from index : ' . $_SESSION['user_login_id'], 0);

                $_SESSION['loggedin'] = true;
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Login successfully .
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>';

                if (isset($_SESSION['party_id'])) {
                    $partyId = $_SESSION['party_id'];

                    $getpersonDetailsSql = "SELECT FIRST_NAME, LAST_NAME FROM person WHERE party_id = '$partyId';";
                    $resultGetpersonDetailsSql = mysqli_query($conn, $getpersonDetailsSql);

                    if ($resultGetpersonDetailsSql) {
                        $row = mysqli_fetch_assoc($resultGetpersonDetailsSql);

                        $_SESSION['first_name'] = $row['FIRST_NAME'];
                        $_SESSION['last_name'] = $row['LAST_NAME'];

                        error_log('First name from index: ' . $_SESSION['first_name'], 0);
                        error_log('Last name from index: ' . $_SESSION['last_name'], 0);
                        header("Location: http://localhost/sysnomy/flatdetails-test/phpfiles/allotteeDetails.php");
                    } else {
                        error_log('Error getting first_name and last_name from session in index file', 0);
                    }
                }
                // Redirect to allotteeDetails page

            }
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071;</strong>Invalid credintials.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        </div>';

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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Login page</title>
</head>

<body>

    <?php require 'phpfiles/navbar.php' ?>
    
    <div class="container my-5 align-items-center">
        <form action="index.php" id="login_page_Form" class="login_page_Form" method="post">
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
<div>


   

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

<?php require 'phpfiles/footer.php'?>

</body>

</html>