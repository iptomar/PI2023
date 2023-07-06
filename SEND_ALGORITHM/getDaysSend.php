<?php
    // Cria uma nova stdClass
    $data = new stdClass();

    // Atribui o título e inicializa o conjunto de dados
    $data->title = 'Volume of Sendings per day';
    $data->data = array();

    // Cria as etiquetas
    $label = array();
    array_push($label, 'days');
    array_push($label, 'Sendings');

    // Adiciona as etiquetas ao conjunto de dados
    array_push($data->data, $label);

    // Acede ao ficheiro filtroSend.csv para fazer a leitura do conteúdo, cuja cada linha
    // desse ficheiro representa as datas em que os envios dos algoritmos foram realizados
    $file = fopen("filtroSend.csv", "r");

    // Inicializa o conjunto de dias
    $days = array();

    // Adiciona o conteúdo de cada linha do ficheiro ao conjunto de dias
    while (!feof($file)) {
        $line = fgets($file);
        // Remove a quebra de linha("\n")
        $line = trim($line);
        array_push($days, $line);
    }

    // Conclui o acesso ao ficheiro
    fclose($file);

    // Remove o zero causado pela quebra de linha
    array_pop($days);

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de datas
    foreach ($days as $day) {
        // Verifica se a data já existe no array de contadores
        if (isset($counters[$day])) {
            // Incrementa o contador da data existente
            $counters[$day]++;
        } else {
            // Cria um novo contador para a data
            $counters[$day] = 1;
        }
    }

    // Obtém a lista completa de dias no formato "Y-m-d"
    $completeDays = array();

    foreach ($counters as $day => $sendings) {
        $completeDays[] = trim($day);
    }

    // Obtém o intervalo completo de dias entre a primeira e última data
    $startDate = min($completeDays);
    $endDate = max($completeDays);

    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod(
        new DateTime($startDate),
        $interval,
         // Adiciona 1 dia para incluir a última data
        new DateTime($endDate . '+1 day')
    );

    // adiciona o contador dos dias que não estão presentes com o valor 0
    foreach ($dateRange as $date) {
        $day = $date->format('Y-m-d');
        if (!isset($counters[$day])) {
            $counters[$day] = 0;
        }
    }

    // Ordena as datas em ordem crescente
    ksort($counters);

    // Inicia um array que irá juntar os dois atributos
    $l = array();

    // Percorre pelos conjuntos de dias e de contadores, sendo este último o número de
    // envios realizados no respectivo dia, e adiciona-os ao array $l, assim criando
    // o dado que irá ser adicionado ao conjunto de dados que irá ser utilizado para
    // conceber um gráfico sobre o número de envios efetuados em cada dia
    foreach ($counters as $day => $sendings) {
        array_push($l, $day);
        array_push($l, $sendings);
        array_push($data->data, $l);
        // Apaga oconteúdo deste array para criar um novo dado
        $l = [];
    }

    echo json_encode($data);
?>
