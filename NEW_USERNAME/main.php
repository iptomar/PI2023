<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css.css"> <!-- Inclusão do arquivo CSS externo -->
    <link rel="shortcut icon" href="icon.png"/> <!-- Ícone da página -->
    <title>PI2023</title> <!-- Título da página -->
    <div id="container">
        <img id="main_icon" src="icon.png"> <!-- Logo principal -->
        <h1 id="title">PI2023</h1> <!-- Título principal -->
        <form action="main_Francisco_Xavier.php" id="backWEGO"> <!-- Formulário para voltar à lista -->
            <button type="submit" id="hideTable"> Lista</button> <!-- Botão para voltar à lista -->
        </form>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!-- Inclusão da biblioteca do Google Charts -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts); // Chama a função para desenhar os gráficos após o carregamento da biblioteca

        function drawCharts() {
            drawWeeksChart(); // Chama a função para desenhar o gráfico por semanas
            drawDaysChart(); // Chama a função para desenhar o gráfico por dias
            drawMonthsChart(); // Chama a função para desenhar o gráfico por meses
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
