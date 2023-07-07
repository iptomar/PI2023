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

// Verifica se foi enviado o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do email e data enviados pelo formulário
    $user_email = $_POST["email"];
    $registro_data = $_POST["data"];
    
    // Verifica se o email está no array de utilizadores
    if (isset($users[$user_email])) {
        // Verifica se a data de registo está no array de contagem de registos do email
        if (isset($users[$user_email][$registro_data])) {
            // Exibe o número de registos para o email e data especificados
            echo "<p class='result'>" . $user_email . " teve " . $users[$user_email][$registro_data] . " registo(s) em " . $registro_data . ".</p>";
        } else {
            // Exibe mensagem de erro caso a data de registo não seja encontrada para o email especificado
            echo "<p class='result'>Nenhum registo encontrado para o email " . $user_email . " na data " . $registro_data . ".</p>";
        }
    } else {
        // Exibe mensagem de erro caso o email especificado não seja encontrado
        echo "<p class='result'>Email " . $user_email . " não encontrado.</p>";
    }
}
?>

<!-- Div para separar a tabela da seção do formulário -->
<div class="table-form">
    <!-- Tabela criada anteriormente -->
    <table class="tbl">
        <thead>
            <tr>
                <th>Email</th>
                <th>Data</th>
                <th>Registos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user_email => $registros) {
                foreach ($registros as $registro_data => $num_registros) {
                    echo "<tr><td>" . $user_email . "</td><td>" . $registro_data . "</td><td>" . $num_registros . "</td></tr>";
                }
            } ?>
        </tbody>
    </table>

    <!-- Formulário para inserir o email e a data a ser verificada -->
    <form class="form" method="POST">
        <fieldset>
            <h2>Verificar registo</h2>
            <div class="form-control">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-control">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>
            </div>
            <div class="form-submit">
                <button type="submit">Verificar</button>
            </div>
        </fieldset>
    </form>
</div>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f7f7f7;
}

.table-form {
    display: flex;
    justify-content: space-between;
    max-width: 1000px;
    margin: 0 auto;
}

.tbl {
    width: 48%;
}

.tbl thead {
    background-color: #282c34;
    color: #fff;
}

.tbl th, .tbl td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

.tbl tbody tr:nth-child(odd) {
    background-color: #f2f2f2;
}

.tbl tbody tr:hover {
    background-color: #ddd;
}

.form {
    width: 48%;
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

.form-control input {
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

.form-submit button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background-color: #3498db;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.result {
    font-weight: bold;
    margin-top: 20px;
    text-align: center;
}
</style>