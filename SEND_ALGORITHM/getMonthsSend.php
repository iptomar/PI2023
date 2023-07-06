<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Volume of Sendings per Month';
    $data->data = array();
    
    //cria as etiquetas
    $label = array();
    array_push($label,'Months');
    array_push($label,'Sendings');

    //adiciona as etiquetas ao conjunto de dados
    array_push($data->data, $label);

    //acede ao ficheiro filtroSend.csv para fazer a leitura do conteudo, cujo cada linha 
    //desse ficheiro representa as datas em que os envios dos algoritmos foram realizados
    $file = fopen("filtroSend.csv", "r");

    //inicializa o cunjunto de meses
    $months = array();

    //adiciona o conteudo de cada linha do ficheiro ao conjunto de meses,
    //, utilizando apenas o ano e o mes da data
    while(!feof($file)) {
        $line = fgets($file); 
        $row = explode("-", $line);
    
        if(isset($row[0]) && isset($row[1]))
            array_push($months, $row[0]."-".$row[1]);
    }

    //conclui o acesso ao ficheiro
    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($months);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de datas
    foreach ($months as $month) {
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$month])) {
            // Incrementa o contador do mês existente
            $counters[$month]++;
        } else {
            // Cria um novo contador para a data
            $counters[$month] = 1;
        }
    }


    //inicia um array que irá juntar os dois atributos
    $l = array();

    //precorre pelos conjuntos de meses e de contadores, sendo este ultimo o nº de
    //envios realizados no respectivo mês, e adiciona-os ao array $l, assim criando
    //o dado que irá ser adicionado ao conjunto de dados que ira ser utilizado para
    //conceber um grafico sobre o nº de envios efetuados em cada mês
    foreach($counters as $months => $sendings){
        array_push($l, $months);
        array_push($l, $sendings);
        array_push($data->data, $l);
        //apaga o conteudo deste array para criar um novo dado
        $l = [];
    }

    echo json_encode($data);
?>