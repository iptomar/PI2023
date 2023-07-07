<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Barras - Dia</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Dia');
            data.addColumn('number', 'Registros');

            <?php
            // Leitura do arquivo
            $filename = "registos.txt";
            $file = fopen($filename, "r");
            if ($file) {
                $countByDay = array();
                while (($line = fgets($file)) !== false) {
                    // Processar cada linha do arquivo
                    $timestamp = strtotime($line);
                    $day = date('Y-m-d', $timestamp);

                    // Contagem de registros por dia
                    if (isset($countByDay[$day])) {
                        $countByDay[$day]++;
                    } else {
                        $countByDay[$day] = 1;
                    }
                }

                // Adicionar os dados ao gráfico
                foreach ($countByDay as $day => $count) {
                    echo "data.addRow(['$day', $count]);";
                }
            }
            fclose($file);
            ?>

            var options = {
                title: 'Registros por Dia',
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