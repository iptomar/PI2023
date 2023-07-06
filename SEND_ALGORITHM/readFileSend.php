<?php
    //acede ao ficheiro logIPRP.csv para fazer a leitura do conteudo
    //cujo são os registros do programa algoritmi 
    $myfile = fopen("../logIPRP.csv", "r"); 

    //separara cada elemento do registro
    while(!feof($myfile)) {
        $line = fgets($myfile); 
        $row = explode(";", $line);
    }

    //conclui o acesso ao ficheiro
    fclose($myfile);
?>