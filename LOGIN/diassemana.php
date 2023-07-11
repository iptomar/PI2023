<?php
// Ler o arquivo de logins
$logFile = 'C:\xampp\htdocs\PI2023\LOGIN\logIPRP.csv';
$logData = file_get_contents($logFile);

// Extrair as datas das entradas de login
$pattern = '/\d{4}-\d{2}-\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[0];

// Verificar se a semana foi submetida via formulário
$selectedWeek = isset($_POST['week']) ? $_POST['week'] : '';

// Calcular a data de início e fim da semana selecionada
$selectedWeekStart = date('Y-m-d', strtotime($selectedWeek));
$selectedWeekEnd = date('Y-m-d', strtotime('+6 days', strtotime($selectedWeekStart)));

// Filtrar as datas para a semana selecionada
$filteredDates = array_filter($dates, function ($date) use ($selectedWeekStart, $selectedWeekEnd) {
    return $date >= $selectedWeekStart && $date <= $selectedWeekEnd;
});

// Contar as ocorrências por dia
$dayCounts = array_count_values($filteredDates);

// Organizar os dados para o Google Charts
$dataArray = [['Dia', 'Log-ins']];
for ($day = 0; $day < 7; $day++) {
    $currentDate = date('Y-m-d', strtotime($selectedWeekStart . " +{$day} days"));
    $count = isset($dayCounts[$currentDate]) ? $dayCounts[$currentDate] : 0;
    $dataArray[] = [$currentDate, $count];
}

// Converter os dados para JSON
$dataJson = json_encode($dataArray);
?>

<!-- Código HTML para listar o gráfico -->
<!DOCTYPE html>
<html>
<head>
    <title>Dias da semana</title>
<link rel="stylesheet" href="css.css">
<form action="graficosemanl.php"><button class="buttons">Voltar</button></form>
    <script type="text/javascript" class="scripts" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" class="scripts">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $dataJson; ?>);

            var options = {
                title: 'Logins por Dia (<?php echo $selectedWeekStart . ' - ' . $selectedWeekEnd; ?>)',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Dia',
                    minValue: 0
                },
                vAxis: {
                    title: 'Log-Ins'
                }
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 1300px; height: 770px;"></div>