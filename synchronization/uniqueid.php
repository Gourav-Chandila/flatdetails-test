<?php
// Generic method for generating a unique ID with a prefix and two random numbers
// function generateUniqueId($prefix, $conn)
// {
//     // Maximum number of attempts to generate a unique ID
//     $maxAttempts = 3;
//     for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
//         // Seed the Mersenne Twister random number generator with the current timestamp and microseconds
//         list($usec, $sec) = explode(" ", microtime());
//         $seed1 = (int) ($sec + $usec * 1000000);
//         $seed2 = $seed1 + 1; // Using a different seed for the second random number
//         // Set the seed for the random number generator
//         mt_srand($seed1);
//         // Generate the first random number
//         $randomNumber1 = mt_rand();
//         // Set the seed for the second random number generator
//         mt_srand($seed2);
//         // Generate the second random number with 4 digits
//         $randomNumber2 = mt_rand(1000, 9999);
//         // Generate a 5-digit number by padding the first random number with leading zeros
//         $idStartingWith1 = str_pad($randomNumber1, 5, '0', STR_PAD_LEFT);
//         // Generate a 4-digit number by padding the second random number with leading zeros
//         $idStartingWith2 = str_pad($randomNumber2, 4, '0', STR_PAD_LEFT);
//         // Concatenate the prefix with the 5-digit and 4-digit numbers
//         $uniqueId = $prefix . $idStartingWith1 . $idStartingWith2;

//         // Check if the generated ID already exists in the database
//         $existingIdCheck = mysqli_query($conn, "SELECT party_id FROM test_id WHERE party_id = '$uniqueId'");
//         if (mysqli_num_rows($existingIdCheck) === 0) {
//             // Unique ID found, break out of the loop
//             break;
//         }
//     }
//     // Return the unique ID
//     return $uniqueId;
// }

// // Example usage:
// $generatedId = generateUniqueId('TEST_ID', $conn);
// // echo $generatedId;

// $insertTestId = array(
//     'party_id' => $generatedId,
// );

// insertData("test_id", $insertTestId, $conn);

// Close connection
// mysqli_close($conn);


function generateRandomHex($length) {
    // Generate random bytes
    $randomBytes = random_bytes($length);
    // Convert the binary data to a hexadecimal representation
    $randomHex = bin2hex($randomBytes);
    // Return the generated random hex
    return $randomHex;
}


?>
