<?php
// Function to check if the file format is valid
function checkFileFormat($fileName, $fileDest)
{
    $allowedFormats = array('pdf', 'jpeg', 'jpg', 'png');
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, $allowedFormats)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>&#10071;</strong> Invalid file format of (' . $fileDest . '). Only files with formats: ' . implode(', ', $allowedFormats) . ' are allowed.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>';
        return false;
    }
    return true;
}

// Function to check file size
function checkFileSize($fileName, $fileNameSize, $fileDest)
{
    if ($fileNameSize > 5 * 1024 * 1024) { // 5MB in bytes
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>&#10071;</strong> Invalid file size of (' . $fileDest . '). Only files with a size less than 5MB are valid.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>';
        return false;
    }
    return true;
}

function DocsUploadPhp($dataResId, $conId, $uniquePID1, $fileName, $conn, $fieldNames, $destination)
{
    try {
        // Determine the file type (extension)
        $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check file type and append text to it Using a ternary operator to set the content type
        $imageFileType = ($imageFileType == "pdf") ? "application/pdf" : "image/$imageFileType";
        $fieldNamesStr = implode(', ', $fieldNames);

        // Start a database transaction
        mysqli_begin_transaction($conn);



        // Get the lowercase file extension from $fileName
        $imageFileType1 = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        // Check if the file extension is one of ['jpeg', 'jpg', 'png'] and stores in DATA_RESOURCE_TYPE_ID
        $dataResourceTypeId = in_array($imageFileType1, ['jpeg', 'jpg', 'png']) ? 'IMAGE_OBJECT' : 'PDF_OBJECT';



        // Construct the SQL query to insert document information
        $sql = "INSERT INTO data_resource (`DATA_RESOURCE_ID`, `DATA_RESOURCE_TYPE_ID`, `MIME_TYPE_ID`, `DATA_RESOURCE_NAME`, `OBJECT_INFO`) 
                VALUES ('$dataResId', '$dataResourceTypeId', '$imageFileType', '$fileName', '$destination')";

        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting into data_resource table: " . mysqli_error($conn));
        }

        if ($dataResourceTypeId === "IMAGE_OBJECT") {
            $insertImageDataSql = "INSERT INTO `image_data_resource` (`DATA_RESOURCE_ID`,`IMAGE_DATA`) VALUES ('$dataResId','$fileName')";
            if (!mysqli_query($conn, $insertImageDataSql)) {
                throw new Exception("Error inserting into image_data_resource  table: " . mysqli_error($conn));
            }
        } else {
            $insertPdfDataSql = "INSERT INTO `pdf_data_resource` (`DATA_RESOURCE_ID`,`PDF_DATA`) VALUES ('$dataResId','$fileName')";
            if (!mysqli_query($conn, $insertPdfDataSql)) {
                throw new Exception("Error inserting into pdf_data_resource table: " . mysqli_error($conn));
            }
        }


        // After successfully inserting into data_resource, insert into content table
        $insertPartyContentSql = "INSERT INTO `content` (`CONTENT_ID`, `CONTENT_TYPE_ID`, `DATA_RESOURCE_ID`,`STATUS_ID`,`CONTENT_NAME`) VALUES ('$conId', 'DOCUMENT', '$dataResId','CTNT_IN_PROGRESS','$fileName')";



        if (!mysqli_query($conn, $insertPartyContentSql)) {
            throw new Exception("Error inserting into content table: " . mysqli_error($conn));
        }



        // Insert into party_content table
        $insertPartyContentSql = "INSERT INTO `party_content` (`PARTY_ID`, `CONTENT_ID`,`PARTY_CONTENT_TYPE_ID`) VALUES ('$uniquePID1', '$conId','$fieldNamesStr')";

        if (!mysqli_query($conn, $insertPartyContentSql)) {
            throw new Exception("Error inserting into party_content table: " . mysqli_error($conn));
        }

        // Commit the transaction if everything was successful
        mysqli_commit($conn);

        // Generate a unique ID for this alert
        $alertId = uniqid();
        // Document uploaded successfully
        echo '<div id="' . $alertId . '" class="alert alert-success alert-dismissible fade show" role="success">
        <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded successfully.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        </div>';
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        mysqli_rollback($conn);

        // Error uploading document, but do not show the database error message to the user
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>&#10071;</strong> Error uploading Documents (' . $fieldNamesStr . ').. Please try again later.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"></span>
          </button>
          </div>';
        // Log the database error for internal use
        error_log("Database error: " . $e->getMessage());
    }
}




// Array of file names and file fields to process
$filesToProcess = array(
    'adharupload' => 'ADHARCARD',
    'pancardupload' => 'PANCARD',
    'allotmentletterupload' => 'ALLOTMENT_LETTER',
    'bbaupload' => 'BBA',
    'bankreceiptupload' => 'BANK_RECEIPT',
    'paymentreceiptupload' => 'PAYMENT_RECEIPT'
);

foreach ($filesToProcess as $fileField => $fileType) {
    $fileName = $_FILES[$fileField]['name'];
    $fileSize = $_FILES[$fileField]['size'];

    if (!empty($fileName)) {

        $userUploadDir = 'phpfiles/uploads/' . $uniquePID1 . '/';
        if (!is_dir($userUploadDir)) {
            mkdir($userUploadDir, 0755, true); // Create the directory and its parent directories
        }

        if (checkFileSize($fileName, $fileSize, $fileType) && checkFileFormat($fileName, $fileType)) {
            $filename = $uniquePID1 . '_' . $fileType . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $destination = $userUploadDir . $filename;

            if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $destination)) {
                $fieldNames = [$fileType];
                $dataResId = generateUniqueId($conn, "DATA", "data_resource");
                // $conId = generateContentId($conn);
                $conId = generateUniqueId($conn,"CONTID","content");
                DocsUploadPhp($dataResId, $conId, $uniquePID1, $fileName, $conn, $fieldNames, $destination);
                $uploadedFiles[] = $fileType;
            } else {
                echo "Error moving $fileType uploaded file.";
            }

        }
    }
}

?>