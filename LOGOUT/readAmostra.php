<?php
// Abrir o arquivo CSV para leitura
$file = fopen("logout_amostra.csv", "r");

// Criar um array vazio para armazenar os objetos
$data = [];

// Ler cada linha do arquivo
while (($line = fgets($file)) !== false) {
    // Dividir a linha em colunas usando o ponto e vírgula (;) como separador
    $columns = explode(";", $line);

    // Extrair as colunas individuais
    $id = $columns[0];
    $date = $columns[1];
    $ip = $columns[2];
    $comando = $columns[3];
    $email = $columns[4];
    $status = $columns[5];
    $version = $columns[6];

    // Separar a data em ano, mês, dia e hora
    $dateTime = new DateTime($date);
    $ano = $dateTime->format('Y');
    $mes = $dateTime->format('m');
    $dia = $dateTime->format('d');
    $hora = $dateTime->format('H:i:s');

    // Criar um objeto com as colunas
    $obj = [
        'id' => $id,
        'ano' => $ano,
        'mes' => $mes,
        'dia' => $dia,
        'hora' => $hora,
        'ip' => $ip,
        'comando' => $comando,
        'email' => $email,
        'status' => $status,
        'version' => $version
    ];

    // Adicionar o objeto ao array de dados
    $data[] = $obj;
}

// Fechar o arquivo
fclose($file);

// Converter o array de dados para JSON
$jsonData = json_encode($data);
?>