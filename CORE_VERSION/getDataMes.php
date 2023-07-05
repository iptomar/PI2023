<?php
    $data = new stdClass();
    $data->title = 'Count per month';
    $data->data = array();
    
    $label = array();
    array_push($label, 'months');
    array_push($label, 'count');

    array_push($data->data, $label);

    $file = fopen("teste.csv", "r");
    $days = array();
    while(!feof($file)) {
        $line = fgets($file); 
        array_push($days, $line);
    }
    fclose($file);

    array_pop($days);
    $counters = [];
    // Itera sobre a lista de datas
    foreach ($days as $day) {
        // Obtém apenas o mês da data
        $month = date('M', strtotime($day));

        // Verifica se o mês já existe no array de contadores
        if (isset($counters[$month])) {
            // incrementa o contador dos meses
            $counters[$month]++;
        } else {
            // Cria um novo contador para o mês x
            $counters[$month] = 1;
        }
    }

    foreach($counters as $month => $count){
        $l = array();
        array_push($l, $month);
        array_push($l, $count);
        array_push($data->data, $l);
    }

    $encodedData = json_encode($data);
    $encodedDataWithLineBreaks = implode("\n", explode(",", $encodedData));

    echo $encodedDataWithLineBreaks;
?>
