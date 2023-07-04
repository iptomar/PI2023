<?php
$myfile = fopen("logout_amostra.csv", "r");
//recebe toda a informação do ficheiro
$data = array();

$alturas = new stdClass();
$alturas->title = 'Logouts';
$alturas->data = array();

// Adiciona o cabeçalho das colunas
array_push($alturas->data, ['Ano', 'Atual', 'Antigo']);

while (($line = fgets($myfile)) !== false) {
    // Separa o conteúdo quando encontra o caractere ";"
    $fields = explode(";", $line);
    array_push($data, $fields);
    
    // Extrai a altura separando o conteúdo quando encontra o caractere "-"
    $datas = explode("-", $fields[1]);
    $altura = array();
    array_push($altura, intval($datas[1]));
    
    array_push($altura, 1);
    array_push($altura, 20);

    array_push($alturas->data, $altura);
}


fclose($myfile);

echo json_encode($alturas);



/*
// Exibir os dados e alturas
print_r($data);
echo"<br><br><br>";
print_r($alturas);


echo"<br><br>";
for($i = 0; $i < count($alturas); $i++){
    print_r($alturas[$i]);
    echo"<br>";
}
*/
?>