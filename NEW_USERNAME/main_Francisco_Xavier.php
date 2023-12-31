<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <title>PI2023</title>
    <link rel="shortcut icon" href="icon.png"/>
</head>

<body>
  <div id="container">
    <img id="main_icon" src="icon.png">
    <h1 id="title">PI2023</h1>

    
        <form id="searchForm">
            <input type="text" id="searchInput" placeholder="Enter username">
            <button type="button" onclick="searchByEmail()">Search</button>
        </form>
    


  <form id="charts">
    <button type="button" onclick = "location.href = 'days.php'" id = "hideTable"> Gráfico dos Dias</button>
    <button type="button" onclick = "location.href = 'main.php'" id = "hideTable"> Gráficos dos Semanas</button>
    <button type="button" onclick = "location.href = 'Month.php'" id = "hideTable"> Gráfico dos Meses</button>
  </form>
  </div>
</body>

<script>
    var buttonClicked = false;

    function searchByEmail() {
    buttonClicked = true;

    // Get the input value
    var email = document.getElementById("searchInput").value;

    // Get the table body element
    var tableBody = document.querySelector("#table tbody");

    // Clear the table body
    tableBody.innerHTML = "";

    // Fetch and parse the CSV data
    fetch("logIPRP.csv")
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Failed to fetch the CSV file.");
            }
            return response.text();
        })
        .then(function(csvText) {
            // Parse the CSV data
            var rows = csvText.split("\n");

            // Loop through the CSV data and display rows matching the email
            for (var i = 0; i < rows.length; i++) {
                var rowValues = rows[i].split(";");
                var emailName = rowValues[4] ?? "";
                var explodedEmailName = emailName.split(" ");
                var rowEmail = explodedEmailName[0] ?? "";
                var name = explodedEmailName[1] ?? "";

                // Check if the email matches and the email address exists
                if (rowEmail.toLowerCase() === email.toLowerCase() && rowEmail.length > 1) {
                    // Create a new table row
                    var newRow = document.createElement("tr");

                    // Create table cells for each data column
                    var dateCell = document.createElement("td");
                    dateCell.textContent = rowValues[1];
                    newRow.appendChild(dateCell);

                    var emailCell = document.createElement("td");
                    emailCell.textContent = rowEmail;
                    newRow.appendChild(emailCell);

                    var nameCell = document.createElement("td");
                    nameCell.textContent = name;
                    newRow.appendChild(nameCell);

                    // Append the new row to the table body
                    tableBody.appendChild(newRow);
                }
            }

            // Check if no rows were found (empty input box)
            if (tableBody.childElementCount === 0) {
                location.reload();
            }
        })
        .catch(function(error) {
            console.log("Error: " + error.message);
        });
}



    function displayCompleteTable() {
        // Get the table body element
        var tableBody = document.querySelector("#table tbody");

        // Fetch and parse the CSV data
        fetch("logIPRP.csv")
            .then(function(response) {
                if (!response.ok) {
                    throw new Error("Failed to fetch the CSV file.");
                }
                return response.text();
            })
            .then(function(csvText) {
                // Parse the CSV data
                var rows = csvText.split("\n");

                // Loop through the CSV data and display all rows
                for (var i = 0; i < rows.length; i++) {
                    var rowValues = rows[i].split(";");
                    var emailName = rowValues[4] ?? "";
                    var explodedEmailName = emailName.split(" ");
                    var email = explodedEmailName[0] ?? "";
                    var name = (explodedEmailName.length >= 2) ? explodedEmailName[1] : "No name";

                    // Create a new table row
                    var newRow = document.createElement("tr");

                    // Create table cells for each data column
                    var dateCell = document.createElement("td");
                    dateCell.textContent = rowValues[1];
                    newRow.appendChild(dateCell);

                    var emailCell = document.createElement("td");
                    emailCell.textContent = email;
                    newRow.appendChild(emailCell);

                    var nameCell = document.createElement("td");
                    nameCell.textContent = name;
                    newRow.appendChild(nameCell);

                    // Append the new row to the table body
                    tableBody.appendChild(newRow);
                }
            })
            .catch(function(error) {
                console.error(error);
            });
    }

    displayCompleteTable();
</script>


<body>
    <!-- Table section -->
    <table id="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Email</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Open the CSV file
            $fileHandle = fopen('logIPRP.csv', 'r');

            // Check if the file opened successfully
            if ($fileHandle === false) {
                die("Failed to open the CSV file.");
            }

            // Output the header row (optional)
            $header = fgetcsv($fileHandle);

            // Process and output the remaining rows
            while (($row = fgetcsv($fileHandle)) !== false) {
                $lineValues = array_map("trim", explode(";", $row[0]));
                $date = $lineValues[1] ?? "";
                $emailName = $lineValues[4] ?? "";
                $explodedEmailName = explode(" ", $emailName);
                $email = $explodedEmailName[0] ?? "";
                $name = (count($explodedEmailName) >= 2) ? $explodedEmailName[1] : "No name";

                // Output a table row for each user
                echo "<tr>";
                echo "<td>" . $date . "</td>";
                echo "<td>" . $email . "</td>";
                echo "<td>" . $name . "</td>";
                echo "</tr>";
            }

            // Close the table
            fclose($fileHandle);
            ?>
        </tbody>
    </table>


    <footer>
        <div id="footer">
            <p>Francisco e Xavier</p>
            <p>PI2023</p>
        </div>
    </footer>

    <script>
        // Load Google Charts library
        google.charts.load("current", { packages: ["corechart"] });
        google.charts.setOnLoadCallback(drawChart);
    </script>
</body>
</html>
