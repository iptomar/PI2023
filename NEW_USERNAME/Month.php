<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css"> <!-- Inclusão do arquivo CSS externo -->
    <link rel="shortcut icon" href="icon.png"/> <!-- Ícone da página -->
    <title>PI2023</title> <!-- Título da página -->
    <div id="container">
        <img id="main_icon" src="icon.png"> <!-- Logo principal -->
        <h1 id="title">PI2023</h1> <!-- Título principal -->
        <form id="charts">
            <button type="button" id="hideTable" onclick="location.href ='main_Francisco_Xavier.php'"> Lista</button> <!-- Botão para voltar à lista -->
            <button type="button" id="hideTable" onclick="location.href = 'days.php'" > Gráficos dos Dias</button> <!-- Botão para voltar à lista -->
            <button type="button" id = "hideTable" onclick="location.href = 'main.php'"  > Gráficos dos Semanas</button>
        </form>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Inclusão da biblioteca do Google Charts -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts); // Chama a função para desenhar os gráficos após o carregamento da biblioteca

        function drawCharts() {
            drawMonthsChart(); // Chama a função para desenhar o gráfico por semanas
        }

        function drawMonthsChart() {
            // Criação do objeto DataTable para os dados do gráfico por meses
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

            // Opções de configuração do gráfico por meses
            var options = {
                title: 'Estatísticas de Registros por Mês',
                hAxis: {title: 'Mês'},
                vAxis: {title: 'Registros'},
                legend: 'none',
                series: {
                    0: { color: '#32d600' } // Define a cor para a primeira série (barras)
                },
                width: 800,
                height: 600
            };

            // Criação do gráfico de barras por meses
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_month'));
            chart.draw(data, options);
        }
        </script>
</head>
<body>
    
    <!-- Div for the Charts -->
  <div id="chartContainer">
    <!-- Chart for Days -->
    <div id="chart_month" class="chart"></div>
  </div>

  <!-- Just a Footer -->
  <footer>
    <div id="footer">
        <p>Francisco e Xavier</p>
        <p>PI2023</p>
    </div>
  </footer>

</body>

</html>
