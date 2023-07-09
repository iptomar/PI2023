<?php
$myfile = fopen("../logIPRP.csv","r");
$weeks = array();

$data = new stdClass();

//$data->title = 'Volume of Sendings per Week';
$data->data = array();

$label = array();
array_push($label,'Weeks');
array_push($label,'Sendings');

array_push($data->data, $label);

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
    $key = "$w de $a";
    //conta os dias por semana
    if(isset($weeks[$key]))
	    $weeks[$key]++;
    else
	    $weeks[$key] = 1;
}
}

fclose($myfile);
$l = array();
foreach($weeks as $key => $cont){
    array_push($l,$key);
    array_push($l,$cont);
    array_push($data->data,$l);
    $l = [];
}



echo json_encode($data);


?>
