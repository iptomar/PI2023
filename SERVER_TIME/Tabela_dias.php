<?php

// define uma variável padrão para a data
$data_selecionada = "";

// verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_selecionada = trim($_POST['data_selecionada']);
}

if(!empty($_POST["volta"])){

    header("Location: grafico_dias.php");
}

// abre o arquivo "registos.txt" para leitura
$arquivo = fopen("registos.txt", "r");

// inicializa o array $horas_totais
$horas_totais = array();

// lê cada linha do arquivo
while (!feof($arquivo)) {
    $linha = fgets($arquivo);
    // verifica se a linha contém informações relevantes
    if (strpos($linha, "service done") !== false) {
        // extrai a data do serviço
        preg_match('/^\d+;(\d{4}\-\d{2}\-\d{2}).*/', $linha, $data);
        $data = $data[1];
        // extrai o tempo do serviço
        preg_match('/time : (.*?)[^0-9\.]/', $linha, $tempo);
        $tempo = $tempo[1];
        // adiciona o tempo ao total da data correspondente
        if (isset($horas_totais[$data])) {
            $horas_totais[$data] += strtotime($tempo) - strtotime('00:00:00');
        } else {
            $horas_totais[$data] = strtotime($tempo) - strtotime('00:00:00');
        }
    }
}

// fecha o arquivo
fclose($arquivo);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Horas totais por dia</title>
    <style>
        /* estilo do título */
        h1 {
            color: #333;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        /* estilo da página */
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        /* estilo da tabela */
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            font-size: 14px;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 10px;
        }
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        /* estilo do formulário */
        form {
          margin-bottom: 20px;
        }
        label, select {
          font-family: Arial, sans-serif;
          font-size: 14px;
        }
        label {
          display: block;
          margin-bottom: 5px;
        }
        select {
          width: 150px;
        }
        input[type=submit] {
          background-color: #4CAF50;
          color: #fff;
          border: none;
          padding: 10px;
          cursor: pointer;
          border-radius: 5px;
          transition: background-color 0.3s ease;
        }
        input[type=submit]:hover {
          background-color: #3e8e41;
        }
    </style>
</head>
<body>

    <h1>Total de horas por dia</h1>

    <form method="POST">
        <label for="data_selecionada">Selecione uma data:</label>
        <select name="data_selecionada" id="data_selecionada">
            <option value="">Todos os dias</option>
            <?php foreach ($horas_totais as $data => $tempo) { ?>
                <option value="<?php echo $data; ?>" <?php if ($data === $data_selecionada) { echo 'selected'; } ?>><?php echo $data; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Exibir">
        <input type="submit" value="Voltar" name="volta">
    </form>

    <table>
        <tr>
            <th>Data</th>
            <th>Horas totais</th>
        </tr>
        <?php foreach ($horas_totais as $data => $tempo) { ?>
            <?php if ($data_selecionada === "" || $data_selecionada === $data) { ?>
                <tr>
                    <td><?php echo $data; ?></td>
                    <td><?php echo date('H:i:s', $tempo); ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

</body>
</html>