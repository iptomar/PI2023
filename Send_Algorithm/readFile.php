<?php
    $data = new stdClass();

    $data->title = 'Volume of Sendings per day';
    $data->data = array();
    
    $label = array();
    array_push($label,'days');
    array_push($label,'Sendings');

    array_push($data->data, $label);

    $myfile = fopen("../teste.csv", "r"); 
    $columns = array();

    while(!feof($myfile)) {
            $line = fgets($myfile); 
            $fields = explode(";", $line);
            if()
            array_push($columns, $fields);
    }

    fclose($myfile);

    print_r($data);
?>