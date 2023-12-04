<?php
// function to check file format
function checkFileFormat($fileName, $fileDest)
{
    $allowedFormats = array('pdf', 'jpeg', 'jpg', 'png');
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, $allowedFormats)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>&#10071;</strong> Invalid file format of (' . $fileDest . '). Only files with formats: ' . implode(', ', $allowedFormats) . ' are allowed.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';
        return false;
    }
    return true;
}

// function to check file size
function checkFileSize($fileName, $fileNameSize, $fileDest)
{
    if ($fileNameSize > 5 * 1024 * 1024) { // 5MB in bytes
        echo '<div class="alert alert-danger alert-dismissible fade show "  role="alert" >
            <strong>&#10071;</strong> Invalid file size of (' . $fileDest . '). Only files with a size less than 5MB are valid.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';

        return false;
    }
    return true;

}


function uploadDocuments($uniqueID1, $conn, $fieldNames, $fieldValues)
{
    if (!empty($fieldNames)) {
        // echo "\nuploading document";  
        $fieldNamesStr = implode(', ', $fieldNames);
        $fieldValuesStr = implode(', ', $fieldValues);
        // echo "Field Name is :" . $fieldNamesStr . "<br>";
        // echo "Field value is :" . $fieldValuesStr . "<br>";

        $sql = "INSERT INTO user_documents (`user_id`,$fieldNamesStr ) VALUES ('$uniqueID1',$fieldValuesStr )";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // Documents  uploaded and registered successfully
            echo '<div class="alert alert-success alert-dismissible fade show" role="success">
                <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded  successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>';
        } else {
            // Error inserting Document information into the database
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>&#10071;</strong> Error uploading Documents   .
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
            </div>';
        }
    }
}
function uploadDocuments1($docId, $uniqueID1, $fileName, $conn, $fieldNames, $destination)
{
    // Determine the file type (extension)
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    //check file type and append text to it
    // Using a ternary operator to set the content type
    $imageFileType = ($imageFileType == "pdf") ? "application/pdf" : "image/$imageFileType";

    $fieldNamesStr = implode(', ', $fieldNames);
    if (!empty($fieldNames)) {
        // Construct the SQL query to insert document information
        $sql = "INSERT INTO document_resource (`DOCUMENT_RESOURCE_ID`, `DOCUMENT_RESOURCE_TYPE_ID`, `USER_ID`,`MIME_TYPE_ID`,`DOCUMENT_RESOURCE_NAME`,`OBJECT_INFO`) 
                    VALUES ('$docId', '$fieldNamesStr', '$uniqueID1','$imageFileType','$fileName','$destination')";

        // Execute the SQL query
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Document uploaded and registered successfully
            echo '<div class="alert alert-success alert-dismissible fade show" role="success">
                <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>';
        } else {
            // Error inserting Document information into the database
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>&#10071;</strong> Error uploading Documents (' . $fieldNamesStr . ')..
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>';
        }
    }
}


function uploadDocumentsIndex2($docId, $uniquePID1, $fileName, $conn, $fieldNames, $destination)
{
    // Determine the file type (extension)
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    //check file type and append text to it
    //Using a ternary operator to set the content type
    $imageFileType = ($imageFileType == "pdf") ? "application/pdf" : "image/$imageFileType";

    $fieldNamesStr = implode(', ', $fieldNames);
    if (!empty($fieldNames)) {
        // Construct the SQL query to insert document information
        $sql = "INSERT INTO document_resource (`DOCUMENT_RESOURCE_ID`, `DOCUMENT_RESOURCE_TYPE_ID`, `PARTY_ID`,`MIME_TYPE_ID`,`DOCUMENT_RESOURCE_NAME`,`OBJECT_INFO`) 
                    VALUES ('$docId', '$fieldNamesStr', '$uniquePID1','$imageFileType','$fileName','$destination')";

        // Execute the SQL query
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Generate a unique ID for this alert
            $alertId = uniqid();
            // Document uploaded and registered successfully
            echo '<div id="' . $alertId . '" class="alert alert-success alert-dismissible fade show" role="success">
            <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';

            echo '<script>
            setTimeout(function() {
                // Find and remove the alert by its unique ID
                var successAlert = document.getElementById("' . $alertId . '");
                if (successAlert) {
                    successAlert.remove();
                }
            }, 2000);
        </script>';

        } else {
            // Error inserting Document information into the database
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071;</strong> Error uploading Documents (' . $fieldNamesStr . ')..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>';
        }
    }
}
function DocsUploadPhp($docId, $uniquePID1, $fileName, $conn, $fieldNames, $destination)
{
    // Determine the file type (extension)
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    //check file type and append text to it
    //Using a ternary operator to set the content type
    $imageFileType = ($imageFileType == "pdf") ? "application/pdf" : "image/$imageFileType";

    $fieldNamesStr = implode(', ', $fieldNames);
    if (!empty($fieldNames)) {
        // Construct the SQL query to insert document information
        $sql = "INSERT INTO document_resource (`DOCUMENT_RESOURCE_ID`, `DOCUMENT_RESOURCE_TYPE_ID`, `PARTY_ID`,`MIME_TYPE_ID`,`DOCUMENT_RESOURCE_NAME`,`OBJECT_INFO`) 
                    VALUES ('$docId', '$fieldNamesStr', '$uniquePID1','$imageFileType','$fileName','$destination')";

        // Execute the SQL query
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Generate a unique ID for this alert
            $alertId = uniqid();
            // Document uploaded and registered successfully
            echo '<div id="' . $alertId . '" class="alert alert-success alert-dismissible fade show" role="success">
            <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>';

            echo '<script>
            setTimeout(function() {
                // Find and remove the alert by its unique ID
                var successAlert = document.getElementById("' . $alertId . '");
                if (successAlert) {
                    successAlert.remove();
                }
            }, 2000);
        </script>';

        } else {
            // Error inserting Document information into the database
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>&#10071;</strong> Error uploading Documents (' . $fieldNamesStr . ')..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>';
        }
    }
}

?>