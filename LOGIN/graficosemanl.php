<?php
// Ler o arquivo de logins
$logFile = 'C:\Users\andra\Ambiente de Trabalho\teste.txt';
$logData = file_get_contents($logFile);

// Extrair as datas das entradas de login
$pattern = '/\d{4}-\d{2}-\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[0];

// Contar as ocorrências por semana
$weekCounts = array();
foreach ($dates as $date) {
    $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($date)));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
    $week = $startOfWeek . ' - ' . $endOfWeek;
    if (isset($weekCounts[$week])) {
        $weekCounts[$week]++;
    } else {
        $weekCounts[$week] = 1;
    }
}

// Organiza  os dados para o Google Charts
$dataArray = [['Semana', 'Log-ins']];
foreach ($weekCounts as $week => $count) {
    $dataArray[] = [$week, $count];
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
                title: 'Logins por Semana',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Semana',
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