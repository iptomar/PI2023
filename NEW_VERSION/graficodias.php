<!DOCTYPE html>
<html>
<head>
    <title>Registos por Dia</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
        }

        h1 {
            margin-top: 50px;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            margin-top: 30px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .button-container button {
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #3366CC;
            color: #ffffff;
            cursor: pointer;
            margin-right: 10px;
        }

        .button-container button:hover {
            background-color: #245ea8;
        }
    </style>
</head>
<body>
    <h1>Registos por Dia</h1>

    <div class="chart-container">
        <div id="chart_div"></div>
    </div>

    <div class="button-container">
        <button onclick="window.location.href = 'index.php';">Voltar</button>
        <button onclick="window.location.href = 'graficosemanas.php';">Semana</button>
        <button onclick="window.location.href = 'graficohoras.php';">Hora</button>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Dia');
            data.addColumn('number', 'Registros');

            <?php
            // Restante do código para gerar o gráfico
            // Leitura do arquivo
            $filename = "registos.txt";
            $file_contents = file_get_contents($filename);
            if ($file_contents !== false) {
                // Separar o conteúdo em linhas
                $file_lines = explode("\n", $file_contents);

                $countByDay = array();
                foreach ($file_lines as $line) {
                    // Processar cada linha do arquivo
                    $line = trim($line); // Remover espaços em branco no início e no fim da linha
                    if (!empty($line)) {
                        $timestamp = strtotime($line);
                        $day = date('Y-m-d', $timestamp);

                        // Contagem de registros por dia
                        if (isset($countByDay[$day])) {
                            $countByDay[$day]++;
                        } else {
                            $countByDay[$day] = 1;
                        }
                    }
                }

                // Adicionar os dados ao gráfico
                foreach ($countByDay as $day => $count) {
                    echo "data.addRow(['$day', $count]);";
                }
            } else {
                echo "Erro ao ler o arquivo.";
            }
            ?>
             var options = {
                title: 'Registros por Dia',
                width: 600,
                height: 400,
                legend: {position: 'none'},
                colors: ['#3366CC'], // Cor das barras
                vAxis: {
                    title: 'Registos por Dia',
                    minValue: 0,
                    gridlines: {color: '#e4e4e4'},
                    baselineColor: '#e4e4e4'
                },
                hAxis: {
                    title: 'Dia',
                    slantedText: true,
                    slantedTextAngle: 45,
                    textStyle: {
                        fontSize: 12
                    }
                },
                chartArea: {
                    left: 60,
                    top: 60,
                    width: '80%',
                    height: '70%'
                },
                backgroundColor: {
                    fill: '#f9f9f9'
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
            
        
    </script>
</body>
</html>