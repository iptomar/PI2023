<?php

// Redireciona para a página da tabela dos dias
if(!empty($_POST["tabela"])){
    header("Location: Tabela_dias.php");
}

// Ler o conteúdo do ficheiro "registos.txt"
$registos = file_get_contents('registos.txt');

// Dividir o conteúdo em linhas
$linhas = explode("\n", $registos);

// Array para contar o número de registros por dia
$horasPorDia = array();

// Loop pelas linhas
foreach ($linhas as $linha) {
    // Dividir a linha em campos
    $campos = explode(";", $linha);

    // Verificar se a linha contém informações válidas
    if (count($campos) >= 2) {
        // Extrair a data da linha
        $data = explode(" ", $campos[1])[0];

        // Incrementar o contador para o dia correspondente
        if (isset($horasPorDia[$data])) {
            $horasPorDia[$data]++;
        } else {
            $horasPorDia[$data] = 1;
        }
    }
}

foreach ($horasPorDia as &$contador) {
    $contador /= 40;
}

// Construir a tabela de dados para o gráfico do Google Charts
$tabelaDados = array();
$tabelaDados[] = ['Data', 'Número de horas'];
foreach ($horasPorDia as $data => $contador) {
    $tabelaDados[] = [$data, $contador];
}

// Converter a tabela de dados em JSON
$dadosJSON = json_encode($tabelaDados);

// Imprimir o código HTML para exibir o gráfico de barras e o formulário
echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Barras - Número de horas por dia</title>
        <style>
            /* Estilos gerais */
            body {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                margin: 0;
                padding: 0;
                text-align: center;
                background-color: #f5f5f5;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }

            h1 {
                margin-bottom: 30px;
            }

            /* Estilos do gráfico */

            #grafico {
                margin: 0 auto;
            }

            /* Estilos do formulário */

            form {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 50px;
            }

            label {
                margin-right: 10px;
                font-size: 18px;
                font-weight: bold;
            }

            input[type="text"] {
                padding: 10px;
                font-size: 16px;
                border-radius: 5px;
                border: none;
                border: 1px solid #ccc;
                margin-right: 10px;
            }

            input[type="submit"] {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                background-color: #4CAF50;
                color: #fff;
                font-size: 18px;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-right:10px;
            }

            input[type="submit"]:hover {
                background-color: #3e8e41;
            }

            #dataEs{
                font-family:verdana;
                border:2px solid black;
                border-radius:10px black;
                width:30%;
                margin:0px auto;
                margin-top:20px;
                color:blue;
            }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(desenharGrafico);

        function desenharGrafico() {
            var data = new google.visualization.arrayToDataTable($dadosJSON);

            var options = {
                title: 'Número de horas por Dia',
                legend: { position: 'none' },
                chartArea: { width: '80%', height: '70%' },
                hAxis: { title: 'Data' },
                vAxis: { title: 'Número de horas por Dia' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('grafico'));

            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="grafico" style="width: 100%; height: 400px;"></div>
    <div>
        <form method="post">
            <label for="data">Insira a data (AAAA-MM-DD):</label>
            <input type="text" id="data" name="data" />
            <input type="submit" value="Consultar" />
            <input type="submit" value="Formato Tabela" name="tabela" />
        </form>
    </div>
</body>
</html>
HTML;

// Verificar se foi submetido um formulário com uma data
if (isset($_POST['data']) && !empty($_POST['data'])) {
    // Extrair a data do formulário
    $data = $_POST['data'];

    // Ler o conteúdo do arquivo "registos.txt"
    $registos = file_get_contents('registos.txt');

    // Dividir o conteúdo em linhas
    $linhas = explode("\n", $registos);

    // Contador de horas para a data especificada
    $contador = 0;

    // Loop pelas linhas
    foreach ($linhas as $linha) {
        // Dividir a linha em campos
        $campos = explode(";", $linha);

        // Verificar se a linha contém informações válidas
        if (count($campos) >= 2) {
            // Extrair a data da linha
            $dataLinha = explode(" ", $campos[1])[0];

            // Verificar se a data da linha é igual à data do formulário
            if ($dataLinha == $data) {
                // Incrementar o contador para a data correspondente
                $contador++;
            }
        }
    }

    $contador /= 40;

    // Imprimir o código HTML para exibir o número total de horas da data especificada
    echo "<div id='dataEs'>Número total de horas na data especificada: $contador</div>";
}
?>