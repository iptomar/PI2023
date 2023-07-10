<?php
//abre ficheiro inicial
$myfile = fopen("../logIPRP.csv","r");
//cria array
$weeks = array();
//cria uma nova stdClass;
$data = new stdClass();

//$data->title = 'Volume of Sendings per Week';
$data->data = array();

//cria as etiquetas
$label = array();
array_push($label,'Weeks');
array_push($label,'Sendings');

//adiciona as etiquetas ao conjunto de dados
array_push($data->data, $label);

//Adiciona cada linha do ficheiro ao conjunto de semanas
while(!feof($myfile)){
$line = fgets($myfile);
$fields = explode(";",$line);

if(isset($fields[3]) && strcmp(trim($fields[3]), "SEND_ALGORITHM") == 0){
            $time = strtotime($fields[1]);
            $d = date("d",$time);
            $m = date("m",$time);
            $a = date("Y", $time);
            //procura o numero da semana
            $w = date("W", $time);

    //armazena na variável $key as semanas pelo formato "Nºsemana" de "NºAno"
    $key = "$w de $a";
    //conta os dias por semana
    if(isset($weeks[$key]))
	    $weeks[$key]++;
    else
	    $weeks[$key] = 1;
}
}

//fecha o ficheiro
fclose($myfile);


$l = array();
//itera sobre o array, e acrescenta o formato "NºSemana de NºAno" ao array $l
//acrescenta a contagem ao array $l
//acresenta ao elemento data o array $l
foreach($weeks as $key => $cont){
    array_push($l,$key);
    array_push($l,$cont);
    array_push($data->data,$l);
    $l = [];
}


//converte dados para json
echo json_encode($data);


?>
