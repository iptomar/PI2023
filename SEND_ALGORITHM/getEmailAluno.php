<?php
$myfile = fopen("../logIPRP.csv","r");
$openFile = fopen("filtroEmail.csv", "w");
$alunos = array();
while(!feof($myfile)){
    $line = fgets($myfile);
    $fields = explode(";",$line);
//Emails aparecem como users em vez de alunoXXXXX...?
    if(isset($fields[4]) && isset($fields[3]) && strcmp(trim($fields[3]), "SEND_ALGORITHM") == 0){
        fwrite($openFile, $fields[4]."\n");   
    }


}
fclose($openFile);
fclose($myfile);




?>