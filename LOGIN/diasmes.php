<?php
// Ler o arquivo de logins
$logFile = 'C:\xampp\htdocs\PI2023\LOGIN\logIPRP.csv';
$logData = file_get_contents($logFile);

// Extrair as datas das entradas de login
$pattern = '/\d{4}-\d{2}-\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[0];

// Verificar se o mês foi submetido via formulário
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';

// Calcular a data de início e fim do mês selecionado
$selectedMonthStart = date('Y-m-01', strtotime($selectedMonth));
$selectedMonthEnd = date('Y-m-t', strtotime($selectedMonth));

// Filtrar as datas para o mês selecionado
$filteredDates = array_filter($dates, function ($date) use ($selectedMonthStart, $selectedMonthEnd) {
    return $date >= $selectedMonthStart && $date <= $selectedMonthEnd;
});

// Contar as ocorrências por dia
$dayCounts = array_count_values($filteredDates);

// Organizar os dados para o Google Charts
$dataArray = [['Dia', 'Log-ins']];
$currentDate = $selectedMonthStart;
while ($currentDate <= $selectedMonthEnd) {
    $count = isset($dayCounts[$currentDate]) ? $dayCounts[$currentDate] : 0;
    $dataArray[] = [$currentDate, $count];
    $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
}

// Converter os dados para JSON
$dataJson = json_encode($dataArray);
?>

<!-- Código HTML para listar o gráfico -->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css.css">
<form action="graficomensal.php"><button class="buttons">Voltar</button></form>
    <script type="text/javascript" class="scripts" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" class="scripts">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $dataJson; ?>);

            var options = {
                title: 'Logins por Dia (<?php echo $selectedMonthStart . ' - ' . $selectedMonthEnd; ?>)',
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