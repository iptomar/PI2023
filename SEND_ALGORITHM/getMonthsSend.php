<?php
    $data = new stdClass();

    //$data->title = 'Volume of Sendings per Month';
    $data->data = array();
    
    $label = array();
    array_push($label,'Months');
    array_push($label,'Sendings');

    array_push($data->data, $label);

    $file = fopen("filtroSend.csv", "r");
    $months = array();
    while(!feof($file)) {
        $line = fgets($file); 
        $row = explode("-", $line);
      
        if(isset($row[0]) && isset($row[1]))
            array_push($months, $row[0]."-".$row[1]);
    }

    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($months);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de datas
    foreach ($months as $month) {
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$month])) {
            // Incrementa o contador do nome existente
            $counters[$month]++;
        } else {
            // Cria um novo contador para a data
            $counters[$month] = 1;
        }
    }


    //
    $l = array();
    foreach($counters as $months => $sendings){
        array_push($l, $months);
        array_push($l, $sendings);
        array_push($data->data, $l);
        $l = [];
    }

    echo json_encode($data);
?>