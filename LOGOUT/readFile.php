<?php
$myfile = fopen("logout_amostra.csv", "r");
$data = array();
$alturas = array();

while (($line = fgets($myfile)) !== false) {
    // Separa o conteúdo quando encontra o caractere ";"
    $fields = explode(";", $line);
    array_push($data, $fields);
    
    // Extrai a altura separando o conteúdo quando encontra o caractere "-"
    $datas = explode("-", $fields[1]);
    $altura = $datas[0];
    array_push($alturas, $altura);
}

fclose($myfile);

// Exibir os dados e alturas
print_r($data);
echo"<br><br><br>";
print_r($alturas);


echo"<br><br>";
for($i = 0; $i < count($alturas); $i++){
    print_r($alturas[$i]);
    echo"<br>";
}

?>