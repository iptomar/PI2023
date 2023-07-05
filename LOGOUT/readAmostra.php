<?php
// Abrir o arquivo CSV para leitura
$file = fopen("logout_amostra.csv", "r");

// Criar um objeto para armazenar os dados do CSV
$data = new stdClass();
$data->dados = array();

// Ler cada linha do arquivo
while (($line = fgets($file)) !== false) {
    // Dividir a linha em colunas usando o ponto e vírgula (;) como separador
    $columns = explode(";", $line);
    array_push($data->dados, $columns);
}

// Fechar o arquivo
fclose($file);

print_r($data);
?>