<?php
    $data = new stdClass();
    $data->title = 'Count per day';
    $data->data = array();
    
    $label = array();
    array_push($label, 'days');
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
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$day])) {
            // incrementa o contador dos dias
            $counters[$day]++;
        } else {
            // Cria um novo contador para o dia x
            $counters[$day] = 1;
        }
    }
    $l = array();
    foreach($counters as $days => $count){
        array_push($l, $days);
        array_push($l, $count);
        array_push($data->data, $l);
        $l = [];
    }

    echo json_encode($data);
//$encodedData = json_encode($data);
//$encodedDataWithLineBreaks = implode("\n", explode(",", $encodedData));
//echo $encodedDataWithLineBreaks;
?>
