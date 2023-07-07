<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color:#73B9C2; 
    }

    /* Estilos para o gráfico */
    #chart_div {
        margin: 20px;
        height: 500px;
    }

    /* Estilos para o título */
    h1 {
        text-align: center;
        margin-top: 20px;
    }
</style>

<!-- Título da Página -->
<h1>Contagem de Registos</h1>

<!-- Gráfico de Barras do Google Charts -->
<div id="chart_div"></div>

<?php
// Ler o arquivo de registos
$registros = file("registos.txt");

// Inicializar o contador
$contador = array();

// Iterar sobre os registos
foreach ($registros as $registro) {
    // Extrair a hora do registo
    $campos = explode(";", $registro);
    $data_hora = strtotime($campos[1]);

    // Obter o número do mês do registo
    $mes = date('n', $data_hora);

    // Incrementar o contador
    if (!isset($contador[$mes])) {
        $contador[$mes] = 0;
    }

    $contador[$mes]++;
}

// Ordenar o contador pelas chaves (meses)
ksort($contador);

// Transformar os números dos meses em nomes em português
$meses_nome = array(
    1 => "Janeiro",
    2 => "Fevereiro",
    3 => "Março",
    4 => "Abril",
    5 => "Maio",
    6 => "Junho",
    7 => "Julho",
    8 => "Agosto",
    9 => "Setembro",
    10 => "Outubro",
    11 => "Novembro",
    12 => "Dezembro"
);
$contador_nomes = array();
foreach ($contador as $mes => $total) {
    $contador_nomes[$meses_nome[$mes]] = $total;
}

// Verificar se o formulário foi enviado
if ($_GET) {
    // Obter o mês selecionado
    $mes = $_GET['mes'];

    // Verificar se o campo foi preenchido
    if (!empty($mes)) {
        // Filtrar os resultados pelo mês selecionado
        $filtered_counter = array($mes => $contador_nomes[$mes]);
    } else {
        // Mostrar todos os resultados
        $filtered_counter = $contador_nomes;
    }
} else {
    // Mostrar todos os resultados
    $filtered_counter = $contador_nomes;
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Ler o arquivo de registos
        var registros = <?= json_encode($filtered_counter) ?>;

        // Criar uma tabela de dados
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Mês');
        data.addColumn('number', 'Contagem de Registos');

        // Adicionar as linhas de dados
        var rows = [];
        for (var mes in registros) {
            rows.push([ mes, registros[mes] ]);
        }
        data.addRows(rows);

        // Definir as opções de configuração do gráfico
        var options = {
            title: 'Contagem de Registos por Mês',
            legend: { position: 'none' },
            bar: { groupWidth: '75%' }
        };

        // Criar o gráfico e adicioná-lo à página
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>