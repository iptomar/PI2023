<header>
    <link rel="stylesheet" href="css.css">
</header>

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

// Start the table
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>Date</th>';
echo '<th>Email</th>';
echo '<th>Name</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Process and output the remaining rows
while (($row = fgetcsv($fileHandle)) !== false) {
    $lineValues = explode(';', $row[0]);
    $date = $lineValues[1];
    $emailName = $lineValues[4];
    $explodedEmailName = explode(' ', $emailName);
    $email = $explodedEmailName[0];
    $name = (count($explodedEmailName) >= 2) ? $explodedEmailName[1] : "No name";

    // Output a table row for each user
    echo '<tr>';
    echo '<td>' . $date . '</td>';
    echo '<td>' . $email . '</td>';
    echo '<td>' . $name . '</td>';
    echo '</tr>';
}

// Close the table
echo '</tbody>';
echo '</table>';

// Close the file handle
fclose($fileHandle);
?>
