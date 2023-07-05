<?php
    $myfile = fopen("../logIPRP.csv", "r"); 

    while(!feof($myfile)) {
        $line = fgets($myfile); 
        $row = explode(";", $line);
    }

    fclose($myfile);
?>