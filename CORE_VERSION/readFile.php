<?php
    $myfile = fopen("../logIPRP.csv", "r"); 
    $teste = fopen("teste.csv", "w");

    while (!feof($myfile)) {
        $line = fgets($myfile); 
        $row = explode(";", $line);
        if (count($row) >= 4 && strcmp("CORE_VERSION", $row[4]) == true) {
            $datetime = substr($row[1], 0, 13) . ':00:00.000';
            $data = $datetime;
            $text = $data . "\n";
            fwrite($teste, $text);
        }
    }

    fclose($myfile);
    fclose($teste);
?>
