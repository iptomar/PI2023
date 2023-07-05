<?php
    $data = new stdClass();

    $data->title = 'Volume of Sendings per day';
    $data->data = array();
    
    $label = array();
    array_push($label,'days');
    array_push($label,'Sendings');

    array_push($data->data, $label);

    $file = fopen("filtroSend.csv", "r");
    $days = array();
    while(!feof($file)) {
        $line = fgets($file); 
        array_push($days, $line);
    }

    fclose($file);

    //remove o elemento que dizia DATA/HORA
    array_shift($days);

    //remove o zero causado pela quebra de linha 
    array_pop($days);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de datas
    foreach ($days as $day) {
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$day])) {
            // Incrementa o contador do nome existente
            $counters[$day]++;
        } else {
            // Cria um novo contador para a data
            $counters[$day] = 1;
        }
    }


    //
    $l = array();
    foreach($counters as $days => $sendings){
        array_push($l, $days);
        array_push($l, $sendings);
        array_push($data->data, $l);
        $l = [];
    }



    echo json_encode($data);
?>