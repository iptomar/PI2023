<?php
    $data = new stdClass();
    $data->title = 'Entradas por Semanas';
    $data->data = array();
    
    $label = array();
    array_push($label, 'semana');
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
        $semana = date('W', strtotime($day));

        // Verifica se o semana já existe no array de contadores
        if (isset($counters[$semana])) {
            // incrementa o contador dos meses
            $counters[$semana]++;
        } else {
            // Cria um novo contador para o semana x
            $counters[$semana] = 1;
        }
    }

    foreach($counters as $semana => $count){
        $l = array();
        array_push($l, $semana);
        array_push($l, $count);
        array_push($data->data, $l);
    }

    echo json_encode($data);
//$encodedData = json_encode($data);
//$encodedDataWithLineBreaks = implode("\n", explode(",", $encodedData));
//echo $encodedDataWithLineBreaks;
?>
