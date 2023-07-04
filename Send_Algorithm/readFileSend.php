<?php
    $myfile = fopen("../logIPRP.csv", "r"); 
    $filtroFile = fopen("filtroSend.csv", "w");

    while(!feof($myfile)) {
        $line = fgets($myfile); 
        $row = explode(";", $line);
        if(count($row) >= 4 && strcmp("SEND_ALGORITHM", $row[4]) == true){
            $amd = substr($row[1], 0, 10);
            $text = $amd."\n";
            fwrite($filtroFile, $text);
        }
    }

    fclose($myfile);
    fclose($filtroFile);
?>