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
                $csvFile = 'PI/PI2023/logIPRP.csv';

                // Open the CSV file
                $fileHandle = fopen($csvFile, 'r');

                // Check if the file opened successfully
                if ($fileHandle === false) {
                    die("Failed to open the CSV file.");
                }

                // Read the header row
                $header = fgetcsv($fileHandle);

                // Read the remaining rows and display them
                while (($row = fgetcsv($fileHandle)) !== false) {
                    echo '<tr>';
                    echo '<td>' . $row[0] . '</td>'; // Name column
                    echo '</tr>';
                }

                // Close the file handle
                fclose($fileHandle);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
