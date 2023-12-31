<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Volume of Sendings per hour of the day ...';
    $data->data = array();
    
    //cria as etiquetas
    $label = array();
    array_push($label,'Hours');
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
        $line = trim($line);
        array_push($days, $line);
    }

    //conclui o acesso ao ficheiro
    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($days);

    //acede ao ficheiro filtrohour.csv para fazer a leitura do conteudo, cujo cada linha 
    //desse ficheiro representa as horas em que os envios dos algoritmos foram realizados
    $file = fopen("filtrohour.csv", "r");

    //inicializa o cunjunto de horas
    $hours = array();

    //adiciona o conteudo de cada linha do ficheiro ao conjunto de horas 
    while(!feof($file)) {
        $line = fgets($file); 
        $line = trim($line);
        array_push($hours, $line);
    }

    //conclui o acesso ao ficheiro
    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($hours);

    $daysHours = array();
    $dh = array();

    for($i=0; $i<count($hours); $i++){
        array_push($dh, $days[$i]);
        array_push($dh, $hours[$i]);
        array_push($daysHours, implode("_", $dh));
        $dh = [];
    }

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de horas
    foreach ($daysHours as $dayHour) {
        // Verifica se a hora já existe no array de contadores
        if (isset($counters[$dayHour])) {
            // Incrementa o contador da hora existente
            $counters[$dayHour]++;
        } else {
            // Cria um novo contador para a hora
            $counters[$dayHour] = 1;
        }
    }


    //inicia um array que irá juntar os dois atributos
    $l = array();

    //precorre pelos conjuntos de horas e de contadores, sendo este ultimo o nº de
    //envios realizados no respectiva hora, e adiciona-as ao array $l, assim criando
    //o dado que irá ser adicionado ao conjunto de dados que irá ser utilizado para
    //conceber um grafico sobre o nº de envios efetuados em cada hora
    foreach($counters as $daysHours => $sendings){
        array_push($l, $daysHours);
        array_push($l, $sendings);
        array_push($data->data, $l);
        //apaga o conteudo deste array para criar um novo dado 
        $l = [];
    }

    $sendDayHours = $data->data;
?>
