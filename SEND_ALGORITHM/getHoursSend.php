<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Mode Sendings per Hour';
    $data->data = array();
    
    //cria as etiquetas
    $label = array();
    array_push($label,'hour');
    array_push($label,'Sendings');

    //adiciona as etiquetas ao conjunto de dados
    array_push($data->data, $label);

    //acede ao ficheiro filtrohour.csv para fazer a leitura do conteudo, cujo cada linha 
    //desse ficheiro representa as horas em que os envios dos algoritmos foram realizados
    $file = fopen("filtrohour.csv", "r");

    //inicializa o cunjunto de horas
    $hours = array();

    //adiciona o conteudo de cada linha do ficheiro ao conjunto de horas  
    while(!feof($file)) {
        $line = fgets($file); 
        array_push($hours, $line);
    }

    //conclui o acesso ao ficheiro
    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($hours);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de horas
    foreach ($hours as $hour) {
        // Verifica se a hora já existe no array de contadores
        if (isset($counters[$hour])) {
            // Incrementa o contador do nome existente
            $counters[$hour]++;
        } else {
            // Cria um novo contador para a hora
            $counters[$hour] = 1;
        }
    }


    //inicia um array que irá juntar os dois atributos
    $l = array();

    //precorre pelos conjuntos de horas e de contadores, sendo este ultimo o nº de
    //envios realizados na respectiva hora, e adiciona-os ao array $l, assim criando
    //o dado que irá ser adicionado ao conjunto de dados que ira ser utilizado para
    //conceber um grafico sobre o nº de envios efetuados em cada hora
    foreach($counters as $hours => $sendings){
        array_push($l, $hours);
        array_push($l, $sendings);
        array_push($data->data, $l);
        //apaga o conteudo deste array para criar um novo dado 
        $l = [];
    }

    echo json_encode($data);
?>