<?php
    $myfile = fopen("../logIPRP.csv", "r"); 
    $filtroFile = fopen("filtroSend.csv", "w");

    while(!feof($myfile)) {
        $line = fgets($myfile); 
        $row = explode(";", $line);
        if(count($row) >= 4 && strcmp($row[4], "SEND_ALGORITHM") == true){
            $amd = substr($row[1], 0, 10);
            $text = $amd."\n";
            fwrite($filtroFile, $text);
        }
    }

    fclose($myfile);
    fclose($filtroFile);
?>