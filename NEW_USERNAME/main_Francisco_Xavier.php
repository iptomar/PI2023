<html>
<head>
    <title>Algorithms</title>
</head>
<body>
    <div id="cabeca">
        <h1>Algorithms</h1>
        <p>Data Explorer</p>
    </div>

    <div id="corpo">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Specify the path to your CSV file
                $csvFile = 'logIPRP.csv';

                // Open the CSV file
                $fileHandle = fopen($csvFile, 'r');

                // Check if the file opened successfully
                if ($fileHandle === false) {
                    die("Failed to open the CSV file.");
                }

                // Read the header row
                $header = fgetcsv($fileHandle);

                // Array to store filtered rows
                $filteredRows = array();

                // Filter rows based on a condition
                while (($row = fgetcsv($fileHandle)) !== false) {
                    // Check if the row meets the filtering condition
                        // Explode the filtered row
                        $csvValues = explode(';', $row[0]);
                        
                        // Add the exploded values to the filtered rows array
                        $filteredRows[] = $csvValues;
                }

                // Close the file handle
                fclose($fileHandle);

                // Display the exploded values
                foreach ($filteredRows as $csvValues) {
                    foreach ($csvValues as $value) {
                        echo $value . "<br>";
                    }
                    echo "<br>";
                }
            ?>

            </tbody>
        </table>
    </div>
</body>
</html>
