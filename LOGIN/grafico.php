<?php
// Ler o arquivo de logins
$logFile = 'C:\xampp\htdocs\PI2023\LOGIN\logIPRP.csv';
$logData = file_get_contents($logFile);


// Extrair as datas das entradas de logins
$pattern = '/\d{4}-\d{2}-\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[0];

// Contar as ocorrências de cada data
$dateCounts = array_count_values($dates);

// Preparar os dados para o Google Charts
$dataArray = [['Data', 'Log-ins']];
foreach ($dateCounts as $date => $count) {
    $dataArray[] = [$date, $count];
}

// Converter os dados para JSON
$dataJson = json_encode($dataArray);

$username = '';
$tableHtml = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'username' key exists in the $_POST array
    if (isset($_POST['username'])) {
        // Get the username from the POST request
        $username = $_POST['username'];
        
        // Read and parse the CSV data
        $csvFile = 'logIPRP.csv';
        $csvData = file_get_contents($csvFile);
        $rows = array_map('str_getcsv', explode("\n", $csvData));

        // Filter and retrieve rows matching the username
        $matchingRows = array_filter($rows, function ($row) use ($username) {
            $emailName = $row[4] ?? '';
            $explodedEmailName = explode(' ', $emailName);
            $name = $explodedEmailName[0] ?? '';

            return strtolower($name) === strtolower($username) && strlen($name) > 1;
        });

        // Prepare the table HTML
        $tableHtml = '';
        foreach ($matchingRows as $row) {
            $date = $row[1];
            $emailName = $row[4] ?? '';
            $explodedEmailName = explode(' ', $emailName);
            $email = $explodedEmailName[0] ?? '';
            $name = (count($explodedEmailName) >= 2) ? $explodedEmailName[1] : 'No name';

            $tableHtml .= '<tr>';
            $tableHtml .= "<td>{$date}</td>";
            $tableHtml .= "<td>{$email}</td>";
            $tableHtml .= "<td>{$name}</td>";
            $tableHtml .= '</tr>';
        }
    } else {
        // 'username' key is not set in $_POST, handle the case as needed
        // For example, you can display an error message or redirect the user
        // header("Location: error.php");
        // exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css">
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $dataJson; ?>);

            var options = {
                title: 'Logins por Data',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Data',
                    minValue: 0
                },
                vAxis: {
                    title: 'Log-Ins'
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
        
        function searchByUsername() {
            // Get the input value
            var username = document.getElementById("searchInput").value;

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

                    // Loop through the CSV data and display rows matching the username
                    for (var i = 0; i < rows.length; i++) {
                        var rowValues = rows[i].split(";");
                        var emailName = rowValues[4] ?? "";
                        var explodedEmailName = emailName.split(" ");
                        var email = explodedEmailName[0] ?? "";
                        var name = (explodedEmailName.length >= 2) ? explodedEmailName[1] : "No name";

                        // Check if the username matches and the name has more than one letter
                        if (name.toLowerCase() === username.toLowerCase() && name.length > 1) {
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
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 1960px; height: 800px; float:left; margin-top: 0px;"></div>
    <form  action="main.php" style="width: 700px;height: 500px; display: inline;margin-inline: 160px;">
        <button class="buttons">Voltar</button></form>
    <form method="POST" action="graficohoras.php" style=";width: 700px;height: 500px; display: inline;margin-inline: 160px;">
        <label style="height:800px ; width:400px;" for="date">Selecione uma data:    
        </label>
        <input type="date" id="date" name="date">
        <button class = "buttons" type="submit">Gerar Gráfico
        </button>
    </form>

    <form id="searchForm" style="width: 400px;height: 300px; display: inline;margin-inline: 160px;" method="POST" action="grafico.php ">
        <input type="text" id="searchInput" name="username" placeholder="Enter username">
        <button class="buttons" type="button" onclick="searchByUsername()">Search</button>
    </form>

    <table id="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Email</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $tableHtml; ?>
        </tbody>
    </table>
</body>
</html>
