<?php
    $myfile = fopen("../teste.csv", "r"); 
    $data = array();
    while(!feof($myfile)) {
        $line = fgets($myfile); 
        $fields = explode(";", $line);
        array_push($data, $fields);
    }

    fclose($myfile);

    print_r($data);

    $text = null;

    $filtroFile = fopen("filtroSend.csv", "w");

    foreach ($data as $row) {
        if(strcmp("SEND_ALGORITHM", $row[4]) == true){
            $amd = substr($row[1], 0, 10);
            $text = $amd."\n";
            fwrite($filtroFile, $text);
        }
    }

    fclose($filtroFile);
?>