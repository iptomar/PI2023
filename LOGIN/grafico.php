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

?>

<!-- Código HTML para o gráfico -->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css.css">
    <form action="main.php"><button class="buttons">Voltar</button></form>
    <script type="text/javascript" class="scripts" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" class="scripts">
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

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 800px; height: 400px;"></div>
</body>
</html>