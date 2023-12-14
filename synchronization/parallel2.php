<?php
require_once 'db_connect.php';
require_once 'uniqueid.php';
require_once 'insertData.php';
// $uniqueRandomNumber = time() . uniqid();
// echo $uniqueRandomNumber;
// use Ramsey\Uuid\Uuid;

// $uuid = Uuid::uuid4();
// echo $uuid->toString();
// Generate 16 bytes (128 bits) of random data
// $randomBytes = random_bytes(5);
// // Convert the binary data to a hexadecimal representation
// $randomHex = bin2hex($randomBytes);
// echo $randomHex;
// // Call the function and store the result in a variable


// $uniqueId = generateUniqueId('test',$conn);


// if ($uniqueId) {
//     // Use the generated unique ID as needed
//     // echo "Generated Unique ID: $uniqueId";
// } else {
//     // Handle the case where the unique ID generation fails
//     // echo "Error generating unique ID";
// }

// Call the function with the desired length (e.g., 5) and store the result in a variable
$randomResult = generateRandomHex(5);

// Display the result
echo $randomResult;
$insertTestId = array(
    'party_id' => $randomResult,
);
insertData("test_id", $insertTestId, $conn);
?>
