<?php
// Lê o conteúdo do arquivo "registos.txt"
$file_contents = file_get_contents("registos.txt");

// Separa o conteúdo em linhas
$file_lines = explode("\n", $file_contents);

// Cria um array para armazenar os dados de cada email
$users = array();

// Percorre cada linha do arquivo
foreach ($file_lines as $line) {
    // Extrai o email do utilizador a partir da linha
    preg_match("/;(\S+@\S+)/", $line, $match);
    $user_email = $match[1];

    // Extrai a data do registo a partir da linha
    preg_match("/\d{4}-\d{2}-\d{2}/", $line, $match);
    $registro_data = $match[0];
    
    // Verifica se o email já está no array de utilizadores
    if (!isset($users[$user_email])) {
        // Adiciona o email ao array de utilizadores e inicializa o array de contagem de registos
        $users[$user_email] = array();
    }
    
    // Verifica se a data do registro já está no array de contagem de registos do email
    if (!isset($users[$user_email][$registro_data])) {
        // Inicializa o contador de registos do email na data especificada
        $users[$user_email][$registro_data] = 1;
    } else {
        // Incrementa o contador de registos do email na data especificada
        $users[$user_email][$registro_data]++;
    }
}

// Cria um array para armazenar os dados do gráfico de barras
$graph_data = array();

// Adiciona a primeira linha com os cabeçalhos das colunas
$graph_data[] = array("Data", "Registos");


//Verifica se o botão de voltar foi pressionado
if(!empty($_POST["volta"])){
  header("Location: grafico_dias.php");
}

// Verifica se foi enviado o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera o email enviado pelo formulário
    $user_email = $_POST["email"];

    // Verifica se o email está no array de utilizadores
    if (isset($users[$user_email])) {
        // Percorre cada data de registo e adiciona os dados ao array do gráfico
        foreach ($users[$user_email] as $registro_data => $num_registros) {
            $graph_data[] = array($registro_data, $num_registros);
        }
    } else {
        // Exibe mensagem de erro caso o email especificado não seja encontrado
        echo "<p class='result'>Email " . $user_email . " não encontrado.</p>";
    }
}

// Converte o array para JSON para ser usado pelo Google Charts
$graph_data_json = json_encode($graph_data);
?>

<!-- Div para separar a tabela da seção do formulário -->
<div class="table-form">
    <!-- Formulário para inserir o email a ser verificado -->
    <form class="form" method="POST">
        <fieldset>
            <h2>Verificar registo</h2>
            <div class="form-control">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-submit">
                <button type="submit">Verificar</button>
                <input type="submit" name="volta" value="Voltar">
            </div>
        </fieldset>
    </form>

    <!-- Cria a div que vai conter o gráfico de barras -->
    <div id="grafico"></div>
</div>

<!-- Adiciona o código JavaScript do Google Charts para criar o gráfico de barras -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $graph_data_json; ?>);

        var options = {
            title: 'Registos',
            hAxis: {title: 'Data',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('grafico'));

        chart.draw(data, options);
    }
</script>

<!-- Adiciona o estilo para o gráfico de barras -->
<style>
#grafico {
    width: 100%;
    height: 500px;
}

body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', sans-serif;
  background-color: #f7f7f7;
}

.table-form {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  max-width: 1000px;
  margin: 0 auto;
}

.form {
  width: 400px;
  margin: 10px;
  background-color: #fff;
  border-radius: 5px;
  padding: 20px;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
}

.form h2 {
  margin-top: 0;
  font-size: 24px;
  font-weight: normal;
}

.form-control {
  margin-bottom: 10px;
}

.form-control label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #666;
}

.form-control input[type="email"],
.form-control input[type="date"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  outline: none;
  font-size: 16px;
  color: #333;
}

.form-submit {
  text-align: center;
}

.form-submit button , .form-submit input{
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background-color: #3498db;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  margin-top:10px;
}

.result {
  font-weight: bold;
  margin-top: 20px;
  text-align: center;
}

.grafico-container {
  margin: 10px;
  background-color: #fff;
  border-radius: 5px;
  padding: 20px;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
}
</style>