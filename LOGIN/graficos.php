<!DOCTYPE html>
<html>
<head>
    <form action="main.php" id="backWEGO">
    <button type="submit">BY BY GO BACK TO THE LOBBY</button>
    </form>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawWeeksChart();
            drawDaysChart();
            drawMonthsChart();
        }

        function drawWeeksChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Semana');
            data.addColumn('number', 'Quantidade de Registros');

            <?php
            // Lê o arquivo CSV
            $file = fopen('logIPRP.csv', 'r');

            // Array para armazenar as semanas e a quantidade de registros por semana
            $weeks = array();

            // Lê a primeira linha do arquivo (cabeçalho)
            fgets($file);

            // Processa cada linha do arquivo
            while (($line = fgets($file)) !== false) {
                $fields = explode(';', $line);

                // Extrai a data/hora do registro
                $datetime = strtotime($fields[1]);

                // Obtém o número da semana do ano
                $week = date('W', $datetime);

                // Verifica se a semana já existe no array
                if (isset($weeks[$week])) {
                    // Incrementa o contador da semana
                    $weeks[$week]++;
                } else {
                    // Cria uma nova entrada no array para a semana
                    $weeks[$week] = 1;
                }
            }

            // Fecha o arquivo
            fclose($file);

            // Cria as linhas do gráfico de barras
            foreach ($weeks as $week => $count) {
                echo "data.addRow(['$week', $count]);";
            }
            ?>

            var options = {
                title: 'Estatísticas por Semana',
                hAxis: {title: 'Semana', titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0},
                width: 800,
                height: 600
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_weeks'));
            chart.draw(data, options);
        }

        function drawDaysChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Data');
            data.addColumn('number', 'Quantidade');

            <?php
            // Lê o arquivo CSV
            $file = fopen('logIPRP.csv', 'r');

            // Array para armazenar as contagens por data
            $counts = array();

            // Ignora a primeira linha (cabeçalho)
            fgets($file);

            // Processa cada linha do arquivo
            while (($line = fgets($file)) !== false) {
                $fields = explode(';', $line);

                // Extrai a data/hora da linha
                $datetime = $fields[1];
                $date = explode(' ', $datetime)[0];

                // Incrementa a contagem para a data atual
                if (isset($counts[$date])) {
                    $counts[$date]++;
                } else {
                    $counts[$date] = 1;
                }
            }

            // Fecha o arquivo
            fclose($file);

            // Cria as linhas do gráfico de barras
            foreach ($counts as $date => $count) {
                echo "data.addRow(['$date', $count]);";
            }
            ?>

            var options = {
                title: 'Estatísticas por Data',
                hAxis: {title: 'Data', titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0},
                width: 800,
                height: 600
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_days'));
            chart.draw(data, options);
        }

        function drawMonthsChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Mês');
            data.addColumn('number', 'Registros');

            <?php
            // Função para extrair o mês de uma data
            function extrairMes($data)
            {
                $partes = explode('-', $data);
                return $partes[1];
            }

            // Ler o arquivo e processar os registros
            $registros = [];
            if (($handle = fopen('logIPRP.csv', 'r')) !== false) {
                while (($dados = fgetcsv($handle, 1000, ';')) !== false) {
                    // Ignorar o cabeçalho
                    if ($dados[0] == 'ID') {
                        continue;
                    }

                    // Extrair o mês da data
                    $mes = extrairMes($dados[1]);

                    // Incrementar o contador do mês correspondente
                    if (isset($registros[$mes])) {
                        $registros[$mes]++;
                    } else {
                        $registros[$mes] = 1;
                    }
                }
                fclose($handle);
            }

            // Gerar os dados no formato esperado pelo Google Charts
            $dadosGoogleCharts = [['Mês', 'Registros']];
            foreach ($registros as $mes => $contador) {
                echo "data.addRow(['$mes', $contador]);";
            }
            ?>

            var options = {
                title: 'Estatísticas de Registros por Mês',
                hAxis: {title: 'Mês'},
                vAxis: {title: 'Registros'},
                legend: 'none',
                width: 800,
                height: 600
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_month'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<!-- Chart for Days -->
<div id="chart_days" style="width: 1000px; height: 800px;"></div>
<!-- Chart for Weeks -->
<div id="chart_weeks" style="width: 1000px; height: 800px; margin-bottom: 30px;"></div>
<!-- Chart for Months -->
<div id="chart_month" style="width: 1000px; height: 800px;"></div>
</body>
</html>