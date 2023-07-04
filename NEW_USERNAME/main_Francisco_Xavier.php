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

  <form action="main.php" id="charts">
    <button type="submit" id = "hideTable"> Gráficos</button>
  </form>
  </div>
</body>

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
        drawChart();
    }

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn("string", "Semana");
        data.addColumn("number", "Quantidade de Registros");
        data.addRows([
            <?php
            foreach ($weeks as $week => $count) {
                echo '["Semana ' . $week . '", ' . $count . '],';
            }
            ?>
        ]);

        var options = {
            title: "Estatísticas por Semana",
            hAxis: { title: "Semana", titleTextStyle: { color: "#333" } },
            vAxis: { minValue: 0 },
            width: 800,
            height: 600
        };

        var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
        chart.draw(data, options);
    }

    function isValidDateFormat(dateString) {
        var pattern = /^\d{4}-\d{2}-\d{2}$/;
        return pattern.test(dateString);
    }

    function getDataForDate(selectedDate) {
        // Specify the path to your CSV file
        var csvFile = "logIPRP.csv";

        return fetch(csvFile)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error("Failed to fetch the CSV file.");
                }
                return response.text();
            })
            .then(function(csvText) {
                // Parse the CSV data
                var rows = csvText.split("\n");
                var ipAddresses = [];

                // Process and filter the rows based on the selected date
                for (var i = 0; i < rows.length; i++) {
                    var lineValues = rows[i].split(";");
                    if (lineValues.length >= 3) {
                        var date = lineValues[1].trim(); // Trim the date value

                        // Filter the rows based on the selected date
                        if (date === selectedDate) {
                            var ipAddress = lineValues[2].trim(); // Trim the IP address value
                            ipAddresses.push(ipAddress);
                        }
                    }
                }

                var count = Math.floor(ipAddresses.length / 3); // Divide the count by 3

                // Prepare the chart data
                var chartData = {
                    labels: [selectedDate],
                    datasets: [
                        {
                            label: "Unique IPs",
                            data: [count],
                            borderColor: "blue",
                            fill: false,
                        },
                    ],
                };

                return chartData;
            })
            .catch(function(error) {
                console.error(error);
                return null;
            });
    }

    function enviar() {
        var selectedDate = document.getElementById("selectedDate").value;

        // Ensure the selected date is in the correct format
        if (!isValidDateFormat(selectedDate)) {
            alert("Invalid date format. Please enter the date in yyyy-mm-dd format.");
            return;
        }

        // Display the graph for the selected date
        showGraph();
    }
</script>

<body>
    <!-- PHP code to read and process the CSV file -->
    <?php
    // Lê o conteúdo do arquivo CSV
    $file = fopen('logIPRP.csv', 'r');

    // Lê a primeira linha do arquivo (cabeçalho)
    $headers = fgetcsv($file, 0, ';');

    // Array para armazenar as semanas e a quantidade de registros por semana
    $weeks = array();

    // Lê as linhas restantes do arquivo
    while (($row = fgetcsv($file, 0, ';')) !== false) {
        // Extrai a data/hora do registro
        $datetime = strtotime($row[1]);

        // Obtém o número da semana do ano
        $week = date('W', $datetime);

        // Verifica se a semana já existe no array
        if (isset($weeks[$week])) {
            // Incrementa o contador da semana
            $weeks[$week]++;
        } else {
            // Cria uma nova entrada no array para a semana
            $weeks[$week] = 1;
        }
    }

    // Fecha o arquivo CSV
    fclose($file);
    ?>

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
