<?php
// Ler o arquivo de logins
$logFile = 'C:\xampp\htdocs\PI2023\LOGIN\logIPRP.csv';
$logData = file_get_contents($logFile);

// Extrair as datas e horas das entradas de logins
$pattern = '/(\d{4}-\d{2}-\d{2}) (\d{2}):\d{2}:\d{2}/';
preg_match_all($pattern, $logData, $matches);
$dates = $matches[1];
$hours = $matches[2];

// Verificar se a data foi submetida via formulário
$selectedDate = isset($_POST['date']) ? $_POST['date'] : '';

// Filtrar as datas e horas para a data selecionada
$filteredHours = array();
foreach ($dates as $index => $date) {
    if ($date === $selectedDate) {
        $filteredHours[] = $hours[$index];
    }
}

// Contar as ocorrências de cada hora
$hourCounts = array_count_values($filteredHours);

// Preparar os dados para o Google Charts
$dataArray = [['Hora', 'Log-ins']];
for ($hour = 0; $hour < 24; $hour++) {
    $hourFormatted = sprintf('%02d', $hour); // Formatar a hora para exibição (por exemplo, "01" em vez de "1")
    $count = isset($hourCounts[$hourFormatted]) ? $hourCounts[$hourFormatted] : 0;
    $dataArray[] = [$hourFormatted, $count];
}

// Converter os dados para JSON
$dataJson = json_encode($dataArray);
?>

<!-- Código HTML para o gráfico -->
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css.css">
    <form action="grafico.php"><button class="buttons">Voltar</button></form>
    <script type="text/javascript" class="scripts" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" class="scripts">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $dataJson; ?>);

            var options = {
                title: 'Logins por Hora (<?php echo $selectedDate; ?>)',
                chartArea: {width: '50%'},
                hAxis: {
                    title: 'Hora',
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