<?php

$ficheiro = fopen("registos.txt","r");
$resultado = "";
$x=1;
while(feof($ficheiro) == false){

    $resultado = fgets($ficheiro);
    echo $x."<br>";
    $x++;

}


?>