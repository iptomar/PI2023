<?php
// Ler o arquivo de logins
$logFile = 'C:\xampp\htdocs\PI2023\LOGIN\logIPRP.csv';
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
<link rel="stylesheet" href="css.css">
<title>Grafico por semanas</title>
<div id="nav_bar">
    <form class = "formu" action="diassemana.php" method="POST">
        <button type="button" class="buttons" onclick="location.href='main.php'">Voltar</button>
        <label for="week">Selecione uma semana:     </label>
        <input type="week" id="week" name="week">
        <button type="submit" class="buttons">Gerar Gráfico</button>
    </form>
</div>
    <script class="scripts" type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" class="scripts">
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
    <div id="chart_div" style="width: 1300px; height: 760px;"></div>
</body>
</html>