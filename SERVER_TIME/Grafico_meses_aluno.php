
<?php
    if(!empty($_GET["volta"])){
        header("Location: Grafico_meses.php");
    }
?>
<style>
     {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color:#BDF1F8;
    }

    /* Estilos para o formulário */
    form {
        margin: 20px;
        border:2px solid #73B9C2;
        padding:10px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 4px;
    }

    input[type="text"] {
        padding: 8px;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
        box-shadow: inset 0 0 0 1px #ccc;
        transition: box-shadow 0.2s;
        width: 200px;
    }

    input[type="text"]:focus {
        outline: none;
        box-shadow: inset 0 0 0 2px #4CAF50;
    }

    button[type="submit"], input[type="submit"]{
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 12px;
        cursor: pointer;
    }

    button[type="submit"]:hover ,input[type="submit"]:hover{
        background-color: #3E8E41;
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

<!-- Formulário de Filtro -->
<form method="get">
    <label for="email">E-mail:</label>
    <input type="text" name="email" id="email">
    <button type="submit">Filtrar</button>
    <input type="submit" name="volta" value="Voltar">
</form>

<!-- Gráfico de Barras do Google Charts -->
<div id="chart_div"></div>

<?php
// Ler o arquivo de registos
$registros = file("registos.txt");

// Inicializar o contador
$contador = array();

// Iterar sobre os registos
foreach ($registros as $registro) {
    // Extrair os campos do registo
    $campos = explode(";", $registro);

    // Obter o email do utilizador
    $partes_email = explode(" ", trim($campos[4]));
    $email = $partes_email[0];

    // Obter o número do mês do registo
    $data_hora = strtotime($campos[1]);
    $mes = date('m', $data_hora);

    // Incrementar o contador
    if (!isset($contador[$email])) {
        $contador[$email] = array();
    }

    if (!isset($contador[$email][$mes])) {
        $contador[$email][$mes] = 0;
    }

    $contador[$email][$mes]++;
}

// Verificar se o formulário foi enviado
if ($_GET) {
    // Obter o e-mail e o ano selecionados
    $email = $_GET['email'];

    // Verificar se os campos foram preenchidos
    if (!empty($email) && !empty($ano)) {
        // Filtrar os resultados pelo email e ano selecionados
        $filtered_counter = array($email => array($ano => $contador[$email]));
    } elseif (!empty($email)) {
        // Filtrar os resultados pelo email selecionado
        $filtered_counter = array($email => $contador[$email]);
    } elseif (!empty($ano)) {
        // Filtrar os resultados pelo ano selecionado
        $filtered_counter = array();
        foreach ($contador as $email => $meses) {
            if (isset($meses[$ano])) {
                $filtered_counter[$email] = array($ano => $meses[$ano]);    
            }
        }
    } else {
        // Mostrar todos os resultados
        $filtered_counter = $contador;
    }
} else {
    // Mostrar todos os resultados
    $filtered_counter = $contador;
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

        // Adicionar uma coluna para cada email
        var emails = [];
        for (var email in registros) {
            emails.push(email);
            data.addColumn('number', email);
        }

        // Adicionar as linhas de dados
        var rows = [];
        for (var i = 1; i <= 12; i++) {
            var mes_str = (i < 10) ? '0' + i.toString() : i.toString();
            var row = [ mes_str ];
            for (var j = 0; j < emails.length; j++) {
                var email2 = emails[j];
                if (typeof registros[email2][mes_str] === 'undefined') {
                    row.push(0);
                } else {
                    row.push(registros[email2][mes_str]);
                }
            }
            rows.push(row);
        }
        data.addRows(rows);

        // Definir as opções de configuração do gráfico
        var options = {
            title: 'Contagem de Registos por Mês',
            legend: { position: 'top', maxLines: 3 },
            bar: { groupWidth: '75%' },
            isStacked: true
        };

        // Criar o gráfico e adicioná-lo à página
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>