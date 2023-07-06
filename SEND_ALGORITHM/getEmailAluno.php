<?php
$myfile = fopen("../logIPRP.csv","r");
$openFile = fopen("filtroEmail.csv", "w");
$alunos = array();
while(!feof($myfile)){
    $line = fgets($myfile);
    $fields = explode(";",$line);

    if(isset($fields[4]) && isset($fields[3]) && strcmp(trim($fields[3]), "SEND_ALGORITHM") == 0){
        $linha = $fields[4];
        $substring = strstr($linha,'@',true);
        echo $substring."<br>";
        array_push($alunos, $substring);
        fwrite($openFile, $substring."\n");   
    }


}
fclose($openFile);
fclose($myfile);


?>