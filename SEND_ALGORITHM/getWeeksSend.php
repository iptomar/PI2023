<?php
$myfile = fopen("../logIPRP.csv","r");
$weeks = array();

while(!feof($myfile)){
$line = fgets($myfile);
$fields = explode(";",$line);

if(isset($fields[3]) && strcmp(trim($fields[3]), "SEND_ALGORITHM") == 0){
            $time = strtotime($fields[1]);
            $d = date("d",$time);
            $m = date("m",$time);
            $a = date("Y", $time);
            //vai buscar numero da semana
            $w = date("W", $time);

    //armazena na variável $key as semanas pelo formato $w
    $key = "$w";
    //conta os dias por semana
    if(isset($weeks[$key]))
	    $weeks[$key]++;
    else
	    $weeks[$key] = 1;
}
}

fclose($myfile);

foreach($weeks as $key => $cont){
    echo "$key $cont <br>";
}






?>
