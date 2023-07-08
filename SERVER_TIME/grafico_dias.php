<?php

//Verificação se o botão de redirecionar para outra página foi pressionado ou não
if(!empty($_POST["alunos"])){
    header("Location: Grafico_dias_aluno.php");
}

if(!empty($_POST["volta"])){
    header("Location: index.php");
}


// funções para contar os registros por dia a partir do arquivo 'registos.txt'

// função principal para contar registros por dia
function contarRegistrosPorDia($dataInicio = null, $dataFim = null) {
    $registros = array(); // array para armazenar os registros contados
    $linhas = file('registos.txt'); // carrega as linhas do arquivo 'registos.txt'
    foreach ($linhas as $linha) { // percorre cada linha
        $campos = explode(';', $linha); // separa os campos pelo separador ';'
        $dataHora = substr($campos[1], 0, 10); // extrai a data/hora do campo correspondente
        if (($dataInicio === null || $dataHora >= $dataInicio) && // verifica se a data/hora está entre o intervalo de datas especificado
            ($dataFim === null || $dataHora <= $dataFim)) {
            $dia = substr($dataHora, 8, 2); // extrai o dia da data/hora
            $mes = substr($dataHora, 5, 2); // extrai o mês da data/hora
            $ano = substr($dataHora, 0, 4); // extrai o ano da data/hora
            if (isset($registros[$ano][$mes][$dia])) { // verifica se o dia já foi contado
                $registros[$ano][$mes][$dia]++; // incrementa o contagem para o dia correspondente
            } else {
                $registros[$ano][$mes][$dia] = 1; // inicia a contagem para o dia correspondente
            }
        }
    }
    return $registros; // retorna o array de registros contados
}

// verifica se o formulário foi submetido com o método POST
if (!empty($_POST["consultar"])) {
    $data = $_POST['data']; // extrai a data do formulário
    $dia = intval(substr($data, 8, 2)); // extrai o dia da data
    $mes = intval(substr($data, 5, 2)); // extrai o mês da data
    $ano = intval(substr($data, 0, 4)); // extrai o ano da data
    $registrosDoDia = contarRegistrosPorDia($data, $data); // conta os registros para o dia especificado
    if (isset($registrosDoDia[$ano][$mes][$dia])) { // verifica se há registros para o dia especificado
        echo "<p>O número de registos para o dia $dia/$mes/$ano é: " . $registrosDoDia[$ano][$mes][$dia] . "</p>"; // exibe o resultado da contagem
    } else {
        echo "<p>Não há registos para o dia $dia/$mes/$ano</p>"; // exibe mensagem de que não há registros para o dia especificado
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registos por dia</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <style>
    /* CSS para estilizar a página */

        body {
            font-family: 'Lato', sans-serif;
            background-color: #E4C6A3;
        }
        
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-top: 30px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        p {
            font-size: 16px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align:center;
        }
        
        label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        input[type="date"] {
            font-size: 16px;
            padding: 5px 10px;
            border-radius: 4px;
            border: solid 1px #ddd;
        }
        
        input[type="submit"]{
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color .2s;
        }
        
        input[type="submit"]:hover{
            background-color: #3e8e41;
        }
        
        form{
            margin-top:20px;
            text-align:center;
        }

        #chart_div {
            width: 900px;
            height: 500px;
            margin: 0 auto;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .3);
        }

        /* Adiciona cores personalizadas de fundo para certos elementos */
        input[type="date"]:focus {
            background-color: #fff9e6;
        }
        
        input[type="submit"]{
            background-color: #1e90ff;
        }
        
        #chart_div {
            background-color: #f2f2f2;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    // script para criar o gráfico com o Google Charts API

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var registros = <?php echo json_encode(contarRegistrosPorDia()); ?>; // carrega os registros contados
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Dia');
        for (var ano in registros) {
            for (var mes in registros[ano]) {
                dataTable.addColumn('number', mes + '/' + ano); // adiciona as colunas para cada mês/ano
            }
        }
        var linhas = [];
        for (var dia = 1; dia <= 31; dia++) {
            var linha = [pad(dia)];
            for (var ano in registros) {
                for (var mes in registros[ano]) {
                    linha.push(registros[ano][mes][pad(dia)] || 0); // adiciona os valores para cada dia/mês/ano
                }
            }
            linhas.push(linha);
        }
        dataTable.addRows(linhas);

        var options = {
          title: 'Registos por dia',
          hAxis: {title: 'Dia',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          legend: 'right'
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(dataTable, options);
      }

      function pad(numero) {
        return ("0" + numero.toString()).substr(-2); // função para adicionar um zero à esquerda em números menores que 10
      }
    </script>
</head>
<body>

    <div id="chart_div" style="width: 900px; height: 500px;"></div>

    <form method="post">
        <label for="data">Selecione uma data:</label>
        <input type="date" name="data" id="data">
        <input type="submit" name="consultar" value="Consultar">
        <input type="submit" name="alunos" value="Alunos">
        <input type="submit" name="volta" value="Voltar">
    </form>
</body>
</html>