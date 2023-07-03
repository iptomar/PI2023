<?php
// Specify the path to your CSV file
$csvFile = 'logIPRP.csv';

// Open the CSV file
$fileHandle = fopen($csvFile, 'r');

// Check if the file opened successfully
if ($fileHandle === false) {
    die("Failed to open the CSV file.");
}

// Output the header row (optional)
$header = fgetcsv($fileHandle);

// Process and output the remaining rows
while (($row = fgetcsv($fileHandle)) !== false) {
    $lineValues = explode(';', $row[0]);
    $date = $lineValues[1];
    $emailName = $lineValues[4];
    $explodedEmailName = explode(' ', $emailName);
    $email = $explodedEmailName[0];
    $name = trim($explodedEmailName[1]);

    // Output the extracted data
    echo "Date: $date<br>";
    echo "Email: $email<br>";
    echo "Name: $name<br>";
    echo "<br>";
}

// Close the file handle
fclose($fileHandle);
?>

