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
            <button type="button" id="hideTable" onclick="location.href ='days.php'"> Gráficos dos Dias</button> <!-- Botão para voltar à lista -->
            <button type="button" id = "hideTable" onclick="location.href = 'Month.php'" > Gráfico dos Meses</button>
        </form>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Inclusão da biblioteca do Google Charts -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts); // Chama a função para desenhar os gráficos após o carregamento da biblioteca

        function drawCharts() {
            drawWeeksChart(); // Chama a função para desenhar o gráfico por semanas
        }

       function drawWeeksChart() {
            // Criação do objeto DataTable para os dados do gráfico por semanas
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

            // Opções de configuração do gráfico por semanas
            var options = {
                title: 'Estatísticas por Semana',
                hAxis: { title: 'Semana', titleTextStyle: { color: '#333' } },
                vAxis: { minValue: 0 },
                series: {
                    0: { color: '#32d600' } // Define a cor para a primeira série (barras)
                },
                width: 800,
                height: 600
            };

            // Criação do gráfico de barras por semanas
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_weeks'));
            chart.draw(data, options);
        }


    </script>
    
</head>
<body>
    
    <!-- Div for the Charts -->
  <div id="chartContainer">
    <!-- Chart for Days -->
    <div id="chart_days" class="chart"></div>

    <!-- Chart for Weeks -->
    <div id="chart_weeks" class="chart"></div>

    <!-- Chart for Months -->
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
