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

    <form id="backWEGO"> <!-- Formulário para voltar à lista -->
            <button type="button" id="hideTable" onclick="location.href = 'days.php'" > Gráficos dos Dias</button> <!-- Botão para voltar à lista -->
            <button type="button" id = "hideTable" onclick="location.href = 'main.php'"  > Gráficos dos Semanas</button>
            <button type="button" id = "hideTable" onclick="location.href = 'Month.php'" > Gráfico dos Meses</button>
        </form>
  </div>
</body>


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
