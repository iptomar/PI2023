<?php
// Ler o arquivo de registos
$logFile = 'C:\Users\andra\Ambiente de Trabalho\teste.txt';
$logData = file_get_contents($logFile);

// Extrair as datas de logins do ficheiro de  registos
$pattern = '/\d{4}-\d{2}-\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[0];

// Contar os logins de cada mês
$monthCounts = array();
foreach ($dates as $date) {
    $month = date('F Y', strtotime($date));
    if (isset($monthCounts[$month])) {
        $monthCounts[$month]++;
    } else {
        $monthCounts[$month] = 1;
    }
}

// Organiza os dados para o Google Charts
$dataArray = [['Mês', 'Log-ins']];
foreach ($monthCounts as $month => $count) {
    $dataArray[] = [$month, $count];
}

// Converter os dados para ficheiro JSON
$dataJson = json_encode($dataArray);

?>

<!-- Código HTML para listar o  gráfico -->
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $dataJson; ?>);

            var options = {
                title: 'Logins por Mês',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Mês',
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