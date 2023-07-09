<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Barras - Hora</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Hora');
            data.addColumn('number', 'Registros');

            <?php
            // Leitura do arquivo
            $filename = "registos.txt";
            $file = fopen($filename, "r");
            if ($file) {
                $countByHour = array();
                while (($line = fgets($file)) !== false) {
                    // Processar cada linha do arquivo
                    $timestamp = strtotime($line);
                    $hour = date('H:00', $timestamp);

                    // Contagem de registros por hora
                    if (isset($countByHour[$hour])) {
                        $countByHour[$hour]++;
                    } else {
                        $countByHour[$hour] = 1;
                    }
                }

                // Adicionar os dados ao gráfico
                foreach ($countByHour as $hour => $count) {
                    echo "data.addRow(['$hour', $count]);";
                }
            }
            fclose($file);
            ?>

            var options = {
                title: 'Registros por Hora',
                width: 600,
                height: 400,
                bar: {groupWidth: '95%'},
                legend: {position: 'none'},
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div"></div>
</body>
</html>