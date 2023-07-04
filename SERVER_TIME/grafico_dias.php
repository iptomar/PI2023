<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas - Gráfico de Barras</title>
    <!-- Inclua a biblioteca do Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carrega a biblioteca de visualização do Google Charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Cria uma tabela vazia para os dados
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Data');
            data.addColumn('number', 'Horas');

            <?php
            // Abre o arquivo de registros
            $file = fopen('registos.txt', 'r');

            // Array para armazenar a contagem de registros por data
            $dateCount = array();

            // Lê cada linha do arquivo e conta os registros por data
            while (($line = fgets($file)) !== false) {
                $parts = explode(';', $line);
                $date = explode(' ', $parts[1])[0];
                if (isset($dateCount[$date])) {
                    $dateCount[$date]++;
                } else {
                    $dateCount[$date] = 1;
                }
            }

            // Fecha o arquivo
            fclose($file);

            // Preenche a tabela de dados com os valores da contagem
            foreach ($dateCount as $date => $count) {
                echo "data.addRow(['$date', $count]);";
            }

            // Encontra o dia com mais registros
            $maxCount = 0;
            $maxDate = '';
            foreach ($dateCount as $date => $count) {
                if ($count > $maxCount) {
                    $maxCount = $count;
                    $maxDate = $date;
                }
            }
            ?>

            // Configurações do gráfico de barras
            var options = {
                title: 'Estatísticas - Horas por dia',
                width: 600,
                height: 400,
                hAxis: {
                    title: 'Data'
                },
                vAxis: {
                    title: 'Horas'
                }
            };

            // Cria um novo gráfico de barras e o exibe na página
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);

            // Exibe o dia com mais horas
            document.getElementById('most_horas').innerHTML = "Dia com mais horas de trabalho: " + "<?php echo $maxDate; ?>" + " (" + "<?php echo $maxCount; ?>" + " horas)";
        }
    </script>
</head>
<body>
    <!-- Div onde o gráfico de barras será exibido -->
    <div id="chart_div"></div>

    <!-- Div onde será exibido o dia com mais horas -->
    <div id="most_horas"></div>
</body>
</html>