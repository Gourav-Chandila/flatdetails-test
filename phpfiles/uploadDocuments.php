<?php
// require 'generateDateTime.php';
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
        $currentDateTime = getCurrentTimestamp();
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


        //Associative array takes value in key value pair
        $insertDataResourceSql = array(
            'DATA_RESOURCE_ID' => $dataResId,
            'DATA_RESOURCE_TYPE_ID' => $dataResourceTypeId,
            'MIME_TYPE_ID' => $imageFileType,
            'DATA_RESOURCE_NAME' => $fileName,
            'OBJECT_INFO' => $destination,

        );
        //Insert data in 'Data_resource'
        insertData("data_resource", $insertDataResourceSql, $conn);



        if ($dataResourceTypeId === "IMAGE_OBJECT") {
            //Associative array takes value in key value pair
            $insertImageDataResourceSql = array(
                'DATA_RESOURCE_ID' => $dataResId,
                'IMAGE_DATA' => $fileName,
            );
            //Insert data in 'image_data_resource'
            insertData("image_data_resource", $insertImageDataResourceSql, $conn);

        } else {
            //Associative array takes value in key value pair
            $insertPdfDataResourceSql = array(
                'DATA_RESOURCE_ID' => $dataResId,
                'PDF_DATA' => $fileName,
            );
            //Insert data in 'pdf_data_resource'
            insertData("pdf_data_resource", $insertPdfDataResourceSql, $conn);
        }

        //Associative array takes value in key value pair
        $insertContentSql = array(
            'CONTENT_ID' => $conId,
            'CONTENT_TYPE_ID' => 'DOCUMENT',
            'DATA_RESOURCE_ID' => $dataResId,
            'STATUS_ID' => 'CTNT_IN_PROGRESS',
            'CONTENT_NAME' => $fileName,
        );
        //Insert data in 'content_resource'
        insertData("content", $insertContentSql, $conn);


        //Associative array takes value in key value pair
        $insertPartyContentSql = array(
            'PARTY_ID' => $uniquePID1,
            'CONTENT_ID' => $conId,
            'PARTY_CONTENT_TYPE_ID' => $fieldNamesStr,
            'FROM_DATE' => $currentDateTime,
        );
        //Insert data in 'party_content'
        insertData("party_content", $insertPartyContentSql, $conn);

        // Commit the transaction if everything was successful
        mysqli_commit($conn);

        // Generate a unique ID for this alert
        $alertId = uniqid();
        error_log("[SUCCESS] Document uploaded successfully for party ID $uniquePID1 and file type $fieldNamesStr");
        // Document uploaded successfully
        echo '<div id="' . $alertId . '" class="alert alert-success alert-dismissible fade show" role="success">
        <strong>&#128522;</strong> (' . $fieldNamesStr . '). Uploaded successfully.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
        </div>';
    } catch (Exception $e) {
        error_log("[ERROR] Error uploading document for party ID $uniquePID1 and file type $fieldNamesStr: " . $e->getMessage());
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

        // //it check first occorance in url if allotteeDetails.php exists than "$userUploadDir = 'uploads/' . $uniquePID1 . '/'" else "$userUploadDir = 'phpfiles/uploads/' . $uniquePID1 . '/'; "
        // if (strpos($_SERVER['REQUEST_URI'], '/allotteeDetails.php') !== false) {
        //     $userUploadDir = 'uploads/' . $uniquePID1 . '/';
        // } else {
        //     $userUploadDir = 'uploads/' . $uniquePID1 . '/';
        // }

        $userUploadDir = 'uploads/' . $uniquePID1 . '/';
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
                $conId = generateUniqueId($conn, "CONTID", "content");
                DocsUploadPhp($dataResId, $conId, $uniquePID1, $fileName, $conn, $fieldNames, $destination);
                $uploadedFiles[] = $fileType;
            } else {
                echo "Error moving $fileType uploaded file.";
            }

        }
    }
}

?>