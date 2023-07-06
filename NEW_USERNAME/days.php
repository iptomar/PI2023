<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css"> <!-- Inclusão do arquivo CSS externo -->
    <link rel="shortcut icon" href="icon.png"/> <!-- Ícone da página -->
    <title>PI2023</title> <!-- Título da página -->
    <div id="container">
        <img id="main_icon" src="icon.png"> <!-- Logo principal -->
        <h1 id="title">PI2023</h1> <!-- Título principal -->
        <form id="backWEGO"> <!-- Formulário para voltar à lista -->
            <button type="button" id="hideTable" onclick="location.href = 'main_Francisco_Xavier.php'" > Lista</button> <!-- Botão para voltar à lista -->
            <button type="button" id = "hideTable" onclick="location.href = 'main.php'"  > Gráficos dos Semanas</button>
            <button type="button" id = "hideTable" onclick="location.href = 'Month.php'" > Gráfico dos Meses</button>
        </form>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Inclusão da biblioteca do Google Charts -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts); // Chama a função para desenhar os gráficos após o carregamento da biblioteca

        function drawCharts() {
            drawDaysChart(); // Chama a função para desenhar o gráfico por dias
        }

        function drawDaysChart() {
            // Criação do objeto DataTable para os dados do gráfico por dias
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

            // Opções de configuração do gráfico por dias
            var options = {
                title: 'Estatísticas por Dia',
                hAxis: {title: 'Data', titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0},
                series: {
                    0: { color: '#32d600' } // Define a cor para a primeira série (barras)
                },
                width: 800,
                height: 600
            };

            // Criação do gráfico de barras por dias
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_days'));
            chart.draw(data, options);
        }
        </script>
</head>
<body>
    
    <!-- Div for the Charts -->
  <div id="chartContainer">
    <!-- Chart for Days -->
    <div id="chart_days" class="chart"></div>
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
