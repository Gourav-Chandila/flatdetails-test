<!-- This will handle form submission plus Document upload on local User_details 20/10/23  -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Register User Details</title>
    <style>
        .form-control-upload {
            width: 95%;
            margin-left: 5%;
            padding-left: 2%;
        }

        .alert.fade.hide {
            opacity: 0;
            transition: opacity 1s;
            /* Adjust the duration of the animation */
        }
    </style>
</head>

<body>
    <?php
    require 'phpfiles/uploadDocs.php';
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get user input from the form
        $fullname = $_POST['fullname'];
        $coapplicantname = $_POST['coapplicantname'];
        $phonenumber = $_POST['phonenumber'];
        $sec_phonenumber = $_POST['sec_phonenumber'];
        $emailaddress = $_POST['emailaddress'];
        $address1 = $_POST['address1'];
        $address2 = $_POST['address2'];
        $flatunitnumber = $_POST['flatunitnumber'];

        // Include the database connection file
        require 'phpfiles/localhost_db_connect.php';

        // Check whether the phone number already exists in the database
        $existSql_phoneno = "SELECT user_id FROM `user_details` WHERE phone_number = '$phonenumber'";
        $result_phonenumber = mysqli_query($conn, $existSql_phoneno);

        if (mysqli_num_rows($result_phonenumber) > 0) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
             <strong>&#10071;</strong> Phone number already exists.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            </div>';
        } else {

            //File Generate Unique Id for user
            require 'phpfiles/generateUniqueId.php';

            $uniqueID1 = generateUniqueUserID($conn);
            $uniqueID2 = generateUniqueDocID($conn);
            echo $uniqueID2 . "<br>";
            echo $uniqueID1 . "<br>";
            // Insert user data into the database
            $insertFlatDetailsSql = "INSERT INTO `user_details` (`user_id`, `full_name`, `coapplicant_name`, `phone_number`, `secondary_phone_number`, `email_address`, `address1`,`address2`, `flat_unit_number`) VALUES ('$uniqueID1', '$fullname', '$coapplicantname', '$phonenumber', '$sec_phonenumber', '$emailaddress', '$address1','$address2', '$flatunitnumber')";
            $resultFlatDetailsInsert = mysqli_query($conn, $insertFlatDetailsSql);

            if ($resultFlatDetailsInsert) {
                // Display a success message if the user data is inserted successfully
                echo '<div class="alert alert-success alert-dismissible fade show" role="success" id="myAlert">
                  <strong>&#128522;</strong>  registered successfully.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
                  </div>';
                echo '<script>
                  // Automatically close the alert after 3 seconds (3000 milliseconds)
                  setTimeout(function () {
                      closeAlert();
                  }, 3000); // Adjust the time in milliseconds as needed
              
                  function closeAlert() {
                      var alert = document.getElementById("myAlert");
                      alert.classList.remove("show");
                      alert.classList.add("hide");
                      setTimeout(function () {
                          alert.remove();
                      }, 1000); // Adjust the duration of the animation (in milliseconds)
                  }
              </script>';

                // header("location: phpfiles/Display_data.php"); 
                // Process and upload document files
                $adharFileName = $_FILES['adharupload']['name']; //name of file
                $panFileName = $_FILES['pancardupload']['name'];
                $allotmentLetterFileName = $_FILES['allotmentletterupload']['name'];
                $bbaFileName = $_FILES['bbaupload']['name'];
                $bankReceiptFileName = $_FILES['bankreceiptupload']['name'];
                $paymentReceiptFileName = $_FILES['paymentreceiptupload']['name'];
                $fieldNames = [];
                $fieldValues = [];

                $userUploadDir = 'uploads/' . $uniqueID1 . '/';
                if (!is_dir($userUploadDir)) {
                    mkdir($userUploadDir, 0755, true); // Create the directory and its parent directories
                }

                // Process and upload Aadhar Card file
                if (!empty($adharFileName)) {
                    // Check the file size and format
                    $adharFileSize = $_FILES['adharupload']['size'];
                    $fileDest = "adharcard"; //db header file name 
                    if (checkFileSize($adharFileName, $adharFileSize, $fileDest)) {
                        if (checkFileFormat($adharFileName, $fileDest)) {
                            $filename = $uniqueID1 . '_adharcard.' . pathinfo($adharFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['adharupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "adharcard";
                                $fieldValues[] = "'" . $adharFileName . "'";
                            } else {
                                return "Error moving Adhar Card uploaded file.";
                            }
                        }
                    }
                }
                // Process and upload Pan Card file
                if (!empty($panFileName)) {
                    $panFileSize = $_FILES['pancardupload']['size'];
                    $fileDest = "pancard"; //db header file name 
                    if (checkFileSize($panFileName, $panFileSize, $fileDest)) { //check filesize
                        if (checkFileFormat($panFileName, $fileDest)) { //check file format(extension) valid or not
                            //Create unique file name
                            $filename = $uniqueID1 . '_pancard.' . pathinfo($panFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['pancardupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "pancard";
                                $fieldValues[] = "'" . $filename . "'";
                            } else {
                                return "Error moving Pan Card uploaded file.";
                            }
                        }
                    }
                }


                // Usage for Allotment Letter
                if (!empty($allotmentLetterFileName)) {
                    $allotmentLetterFileSize = $_FILES['allotmentletterupload']['size'];
                    $fileDest = "allotment_letter"; //db header file name 
                    if (checkFileSize($allotmentLetterFileName, $allotmentLetterFileSize, $fileDest)) { //check filesize
                        if (checkFileFormat($allotmentLetterFileName, $fileDest)) {
                            $filename = $uniqueID1 . 'allotment_letter.' . pathinfo($allotmentLetterFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['allotmentletterupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "allotment_letter";
                                $fieldValues[] = "'" . $filename . "'";
                            } else {
                                return "Error moving Allotment letter uploaded file.";
                            }
                        }
                    }
                }
                // Usage for Builder Builder Buyer Agreement (BBA)
                if (!empty($bbaFileName)) {
                    $bbaFileSize = $_FILES['bbaupload']['size'];
                    $fileDest = "bba"; //db header file name 
                    if (checkFileSize($bbaFileName, $bbaFileSize, $fileDest)) { //check filesize
                        if (checkFileFormat($bbaFileName, $fileDest)) {
                            $filename = $uniqueID1 . 'bba.' . pathinfo($bbaFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['bbaupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "bba";
                                $fieldValues[] = "'" . $filename . "'";
                            } else {
                                return "Error moving BBA uploaded file.";
                            }
                        }
                    }
                }
                // Usage for Bank Receipt
                if (!empty($bankReceiptFileName)) {
                    $bankReceiptFileSize = $_FILES['bankreceiptupload']['size'];
                    $fileDest = "bank_receipt"; //db header file name 
                    if (checkFileSize($bankReceiptFileName, $bankReceiptFileSize, $fileDest)) { //check filesize
                        if (checkFileFormat($bankReceiptFileName, $fileDest)) {
                            $filename = $uniqueID1 . 'bank_receipt.' . pathinfo($bankReceiptFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['bankreceiptupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "bank_receipt";
                                $fieldValues[] = "'" . $filename . "'";
                            } else {
                                return "Error moving Bank receipt uploaded file.";
                            }
                        }
                    }
                }
                // Usage for Payment Receipt
                if (!empty($paymentReceiptFileName)) {
                    $paymentReceiptFileSize = $_FILES['paymentreceiptupload']['size'];
                    $fileDest = "payment_receipt"; //db header file name 
                    if (checkFileSize($paymentReceiptFileName, $paymentReceiptFileSize, $fileDest)) { //check filesize
                        if (checkFileFormat($paymentReceiptFileName, $fileDest)) {
                            $filename = $uniqueID1 . 'payment_receipt.' . pathinfo($paymentReceiptFileName, PATHINFO_EXTENSION);
                            $destination = $userUploadDir . $filename;
                            if (move_uploaded_file($_FILES['paymentreceiptupload']['tmp_name'], $destination)) {
                                $fieldNames[] = "payment_receipt";
                                $fieldValues[] = "'" . $filename . "'";
                            } else {
                                return "Error moving Payment receipt uploaded file.";
                            }
                        }
                    }
                }
                // Call a function to upload the document information to the database
                uploadDocuments($uniqueID1, $conn, $fieldNames, $fieldValues);
                // Use JavaScript to redirect to Display_data.php after 3 seconds
                //     echo '<script>
                //    setTimeout(function() {
                //    window.location.href = "Display_data.php";
                //    }, 3000); // 3 seconds
                //    </script>';
    

                // End the script execution
                exit();
            } else {
                // Display an error message if there are issues inserting user data
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" >
                  <strong>&#10071;</strong> There are some technical problems at this time.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
                  </div>';

            }
            // Close the database connection
            mysqli_close($conn);
        }
    }
    ?>

    <div class="container-fluid">

        <div class="container">
            <form id="myForm" action="" method="post" enctype="multipart/form-data">
                <div class="row">

                    <div class="col-md-7 my-3">
                        <div class="form-group ">
                            <label for="fullname">Fullname *</label>
                            <input type="text" name="fullname" class="form-control" id="fullname">
                        </div>


                        <div class="form-group">
                            <label for="coapplicantname">Coapplicant name</label>
                            <input type="text" name="coapplicantname" class="form-control " id="coapplicantname">
                        </div>

                        <div class="form-group ">
                            <label for="phonenumber">Phone number *</label>
                            <input type="text" name="phonenumber" class="form-control custom-input" id="phonenumber">
                        </div>

                        <div class="form-group ">
                            <label for="sec_phonenumber">Secondary phone number <b>optional</b> </label>
                            <input type="text" name="sec_phonenumber" class="form-control custom-input"
                                id="sec_phonenumber">
                        </div>

                        <div class="form-group ">
                            <label for="emailaddress">Email address * </label>
                            <input type="email" name="emailaddress" class="form-control custom-input" id="emailaddress">
                        </div>

                        <div class="form-group ">
                            <label for="address1">Address1</label>
                            <input type="text" name="address1" class="form-control custom-input" id="address1">
                        </div>
                        <div class="form-group ">
                            <label for="address2">Address2</label>
                            <input type="text" name="address2" class="form-control custom-input" id="address2">
                        </div>
                        <div class="form-group ">
                            <label for="flatunitnumber">Flat unit number</label>
                            <input type="text" name="flatunitnumber" class="form-control custom-input"
                                id="flatunitnumber">
                        </div>
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
                                        <div class="row  ">
                                            <div class="col-3 doc_title_no_wrap ">
                                                Adhar card :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload " type="file"
                                                        name="adharupload" value="" id="fileInput">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-3 doc_title_no_wrap">
                                                Pan Card :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload" type="file"
                                                        name="pancardupload" value="" id="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row ">
                                            <div class="col-3 doc_title_no_wrap">
                                                Allotment Letter :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload" type="file"
                                                        name="allotmentletterupload">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row ">
                                            <div class="col-3 doc_title_no_wrap">
                                                BBA :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload" type="file"
                                                        name="bbaupload">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-3 doc_title_no_wrap">
                                                Bank Receipt :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload" type="file"
                                                        name="bankreceiptupload">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-3 doc_title_no_wrap">
                                                Payment Receipt :
                                            </div>
                                            <div class="col-9">
                                                <div class="form-group">
                                                    <input class="form-control form-control-upload" type="file"
                                                        name="paymentreceiptupload">
                                                </div>
                                            </div>
                                        </div>
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

    <script src="jquery/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>
</body>

</html>