<?php
    // Cria uma nova stdClass
    $data = new stdClass();

    // Atribui o título e inicializa o conjunto de dados
    $data->title = 'Mode Sendings per Hour';
    $data->data = array();

    // Cria as etiquetas
    $label = array();
    array_push($label, 'hour');
    array_push($label, 'Sendings');

    // Adiciona as etiquetas ao conjunto de dados
    array_push($data->data, $label);

    // Acede ao ficheiro filtrohour.csv para fazer a leitura do conteudo, cujo cada linha 
    // desse ficheiro representa as horas em que os envios dos algoritmos foram realizados
    $file = fopen("filtrohour.csv", "r");

    // Inicializa o conjunto de horas
    $hours = array();

    // Adiciona o conteudo de cada linha do ficheiro ao conjunto de horas
    while (!feof($file)) {
        $line = fgets($file);
        // Remove a quebra de linha("\n")
        $line = trim($line);
        array_push($hours, $line);
    }

    // Conclui o acesso ao ficheiro
    fclose($file);

    // Remove o zero causado pela quebra de linha
    array_pop($hours);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de horas
    foreach ($hours as $hour) {
        // Verifica se a hora já existe no array de contadores
        if (isset($counters[$hour])) {
            // Incrementa o contador da hora existente
            $counters[$hour]++;
        } else {
            // Cria um novo contador para a hora
            $counters[$hour] = 1;
        }
    }

    // Inicializa um array que irá juntar os dois atributos
    $l = array();

    // Percorre pelos conjuntos de horas e de contadores, sendo este último o número de
    // envios realizados na respectiva hora, e adiciona-os ao array $l, assim criando
    // o dado que irá ser adicionado ao conjunto de dados que irá ser utilizado para
    // conceber um gráfico sobre o número de envios efetuados em cada hora
    for ($i = 0; $i < 24; $i++) {
         // Formata a hora com dois dígitos
        $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
        // Verifica se existe envio para essa hora
        $sendings = isset($counters[$hour]) ? $counters[$hour] : 0; 
        
        array_push($l, $hour);
        array_push($l, $sendings);
        array_push($data->data, $l);
        // Apaga o conteúdo deste array para criar um novo dado
        $l = [];
    }

    // faz o encode do json para que os dados possam ser lidos
    // para que seja possivel criar o grafico
    echo json_encode($data);
?>
