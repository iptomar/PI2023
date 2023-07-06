<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Volume of Sendings per day';
    $data->data = array();
    
    //cria as etiquetas
    $label = array();
    array_push($label,'days');
    array_push($label,'Sendings');

    //adiciona as etiquetas ao conjunto de dados
    array_push($data->data, $label);

    //acede ao ficheiro filtroSend.csv para fazer a leitura do conteudo, cujo cada linha 
    //desse ficheiro representa as datas em que os envios dos algoritmos foram realizados
    $file = fopen("filtroSend.csv", "r");

    //inicializa o cunjunto de dias
    $days = array();

    //adiciona o conteudo de cada linha do ficheiro ao conjunto de dias  
    while(!feof($file)) {
        $line = fgets($file); 
        array_push($days, $line);
    }

    //conclui o acesso ao ficheiro
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


    //inicia um array que irá juntar os dois atributos
    $l = array();

    //precorre pelos conjuntos de dias e de contadores, sendo este ultimo o nº de
    //envios realizados no respectivo dia, e adiciona-os ao array $l, assim criando
    //o dado que irá ser adicionado ao conjunto de dados que ira ser utilizado para
    //conceber um grafico sobre o nº de envios efetuados em cada dia
    foreach($counters as $days => $sendings){
        array_push($l, $days);
        array_push($l, $sendings);
        array_push($data->data, $l);
        //apaga o conteudo deste array para criar um novo dado 
        $l = [];
    }



    echo json_encode($data);
?>