<?php

    if(!empty($_POST["volta"])){
        header("Location: index.php");
    }

    if(!empty($_POST["alunos"])){
        header("Location: Grafico_semanas_aluno.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
    background-color: #f2f2f2;
    font-family: Arial, sans-serif;
    }

    h1 {
        text-align: center;
        color: #4CAF50;
    }

    #chart_div {
        margin: 0 auto;
        width: 800px;
        height: 400px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    .column-chart-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #4CAF50;
    }

    .column-chart-axis-labels {
        font-size: 16px;
        font-weight: bold;
        color: #555;
    }

    .column-chart-bars {
        fill: #4CAF50;
        transition: fill .3s;
    }

    .column-chart-bars:hover {
        fill: #2E8B57;
    }

    form{
			max-width: 200px;
			margin: 0 auto;
			padding: 20px;
			border: 2px solid #333;
			border-radius: 5px;
			background-color: #FDFDFD;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            text-align:center;
            margin-top:10px;
		}
		
		input[type="submit"]{
			background-color: #333;
			color: #FFF;
			font-size: 16px;
			padding: 10px 15px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}
		
		input[type="submit"]:hover{
			background-color: #111;
		}
    </style>
    <title>Gráfico de Barras - Registros por Semana</title>
    <meta charset="utf-8">
    <!-- Adiciona o script da API do Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carrega a API para gerar o gráfico
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Função que desenha o gráfico
        function drawChart() {
            // Cria um objeto DataTable com os dados dos registos
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Semana');
            data.addColumn('number', 'Registos');
            data.addRows([
                <?php
                    $registros = file("registos.txt");

                    $contador = 0;
                    $semana_atual = null;

                    foreach ($registros as $registro) {
                        $campos = explode(";", $registro);
                        $data_hora = strtotime($campos[1]);
                        $semana = date('W', $data_hora);

                        if ($semana_atual !== $semana) {
                            if (!is_null($semana_atual)) {
                                echo "['Semana $semana_atual', $contador],";
                            }
                            $semana_atual = $semana;
                            $contador = 0;
                        }

                        $contador++;
                    }

                    echo "['Semana $semana_atual', $contador]";
                ?>
            ]);

            // Define as opções de visualização do gráfico
            var options = {
                'title': 'Registos por Semana',
                'width': 800,
                'height': 400
            };

            // Cria um novo objeto gráfico de barras
            var chart = new google.visualization.ColumnChart(
                document.getElementById('chart_div'));

            // Desenha o gráfico com os dados e as opções definidas
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <!-- Define um container para o gráfico -->
    <div id="chart_div"></div>
    <form action="" method="post">
        <input type="submit" name="alunos" value="Alunos">
        <input type="submit" name="volta" value="Voltar">
    </form>
</body>
</html>