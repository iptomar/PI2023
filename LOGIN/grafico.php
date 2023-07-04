<?php
// Vai ler o ficheiro .csv
$file = 'C:/xampp/htdocs/PROJETOINTEGRADO1/PI2023/log_MOOC.csv';

// verfifica se o ficheiro existe 
if (!file_exists($file)) {
    die('File not found.');
}

// Le o ficheiro e anlisa os dados do CSV
$csvData = file_get_contents($file);
$rows = explode("\n", $csvData);
$headers = str_getcsv(array_shift($rows)); // extrai os titulos das colunas do ficheiro
$data = [];
foreach ($rows as $row) {
    $rowValues = str_getcsv($row);// analisa os titulos das colunas e guarda os num array como valores no formato csv, sendo que o array e guardado na variavel
    if (count($rowValues) === count($headers)) {// vai verificar se o numero de valores na coluna corrente corresponde ao numero de titulos
        $data[] = array_combine($headers, $rowValues);// se forem iguais a coluna é valida e contem a data correspondente aos titulos
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Vamos incluir a biblioteca Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js%22%3E"></script>
    <script type="text/javascript">
        //Aqui carrega a biblioteca Google Charts e define a função callback
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);
        // funçao que gera o grafico 
        function drawChart() {
            // converte o array PHP $data num objeto datatable do google charts
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            // varivel que vai definir as opções do grafico
            var options = {
                title: 'Your Chart Title',
                // Add other chart options here
            };
            //cria um grafico de linhas usando o objeto datatable e as opções definidas
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 100%; height: 400px;"></div>

    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <!-- Cria cabeçalhos de coluna com os valores dos headers -->
                    <th><?php echo $header; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <!-- Cria células de dados com os valores das linhas -->
                        <td><?php echo str_replace(';' ,'&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;&#9;&nbsp;', $value); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>







