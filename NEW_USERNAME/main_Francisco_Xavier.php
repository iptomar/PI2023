<head>
    <link rel="stylesheet" href="css.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>PI2023</title>
    <link rel="shortcut icon" href="icon.png"/>
</head>


<script>
    function showANDhideTable() {
        var table = document.getElementById("table");
        var hideTable = document.getElementById("hideTable");
        if (table.style.display === "none") {
            table.style.display = "table";
            hideTable.innerHTML = "Esconder tabela";
        } else {
            table.style.display = "none";
            hideTable.innerHTML = "Mostrar tabela";
        }
    }

    function showGraph() {
        var chartCanvas = document.getElementById("chart");
        chartCanvas.style.display = "block";
        createChart();
    }

    function createChart() {
        var chartCanvas = document.getElementById("chart");
        var ctx = chartCanvas.getContext("2d");
        var selectedDate = document.getElementById("selectedDate").value;

        // Get the data for the selected date from the CSV file
        var data = getDataForDate(selectedDate);

        // Chart options
        var options = {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Create the chart
        new Chart(ctx, {
            type: "line",
            data: data,
            options: options
        });
    }

    function getDataForDate(selectedDate) {
  // Specify the path to your CSV file
  var csvFile = "logIPRP.csv";

  return fetch(csvFile)
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Failed to fetch the CSV file.");
      }
      return response.text();
    })
    .then(function (csvText) {
      // Parse the CSV data
      var rows = csvText.split("\n");
      var chartData = {
        labels: [],
        datasets: [
          {
            label: "NewUsername",
            data: [],
            borderColor: "blue",
            fill: false,
          },
        ],
      };

      // Process and filter the rows based on the selected date
      for (var i = 0; i < rows.length; i++) {
        var lineValues = rows[i].split(";");
        var date = lineValues[1];

        // Filter the rows based on the selected date
        if (date === selectedDate) {
          var count = parseInt(lineValues[3]); // Assuming the user count is in the 4th column
          chartData.labels.push(date);
          chartData.datasets[0].data.push(count);
        }
      }

      return chartData;
    })
    .catch(function (error) {
      console.error(error);
      return null;
    });



        // Process and filter the rows based on the selected date
        while ((row = fgetcsv(fileHandle)) !== false) {
            var lineValues = array_map("trim", explode(";", row[0]));
            var date = lineValues[1];

            // Filter the rows based on the selected date
            if (date === selectedDate) {
                var count = parseInt(lineValues[3]); // Assuming the user count is in the 4th column
                chartData.labels.push(date);
                chartData.datasets[0].data.push(count);
            }
        }

        // Close the file handle
        fclose(fileHandle);

        return chartData;
    }
</script>

<?php
// Specify the path to your CSV file
$csvFile = "logIPRP.csv";

// Open the CSV file
$fileHandle = fopen($csvFile, "r");

// Check if the file opened successfully
if ($fileHandle === false) {
    die("Failed to open the CSV file.");
}

// Output the header row (optional)
$header = fgetcsv($fileHandle);

// Start the table
echo "<table id='table'>";
echo "<thead>";
echo "<tr>";
echo "<th>Date</th>";
echo "<th>Email</th>";
echo "<th>Name</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

// button to hide the table and display a button to show a graph using the data from the table
echo "<div id = 'cabeca'>"; 
echo "<img id = 'main_icon'src = 'icon.png'>";
echo "<h1 id='title'>PI2023</h1>";
echo "<div id='buttons'>";
echo "<button id='hideTable' onclick='showANDhideTable()'>Esconder tabela</button>";
echo "<button id='showGraph' onclick='showGraph()'>Mostrar Gráfico</button>";
echo "</div>";
echo "</div>";

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
echo "</tbody>";
echo "</table>";


// Close the file handle
fclose($fileHandle);
?>

<!-- Add a date picker -->
<label for="selectedDate">Select Date:</label>
<input type="date" id="selectedDate">
<br><br>

<!-- Chart canvas -->
<canvas id="chart" style="display: none;"></canvas>
<footer>
    <div id="footer">
        <p>Francisco e Xavier</p>
        <p>PI2023</p>
    </div>
</footer>