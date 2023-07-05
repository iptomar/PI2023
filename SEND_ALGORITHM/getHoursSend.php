<?php
    $data = new stdClass();

    $data->title = 'Volume of Sendings per day';
    $data->data = array();
    
    $label = array();
    array_push($label,'hours');
    array_push($label,'Sendings');

    array_push($data->data, $label);

    $file = fopen("filtrohour.csv", "r");
    $days = array();
    while(!feof($file)) {
        $line = fgets($file); 
        array_push($hours, $line);
    }

    fclose($file);

    //remove o elemento que dizia DATA/HORA
    array_shift($days);

    //remove o zero causado pela quebra de linha 
    array_pop($days);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de datas
    foreach ($hours as $hour) {
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$hour])) {
            // Incrementa o contador do nome existente
            $counters[$hour]++;
        } else {
            // Cria um novo contador para a data
            $counters[$hour] = 1;
        }
    }


    //
    $l = array();
    foreach($counters as $hours => $sendings){
        array_push($l, $hours);
        array_push($l, $sendings);
        array_push($data->data, $l);
        $l = [];
    }



    echo json_encode($data);
?>