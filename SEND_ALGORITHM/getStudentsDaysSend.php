<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Sending per day of the student ...';
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

    //acede ao ficheiro filtroUsers.csv para fazer a leitura do conteudo, cujo cada linha 
    //desse ficheiro representa o aluno que fez o envio do algoritmo
    $file = fopen("filtroUsers.csv", "r");

    //inicializa o cunjunto de horas
    $students = array();

    //adiciona o conteudo de cada linha do ficheiro ao conjunto de estudantes 
    while(!feof($file)) {
        $line = fgets($file); 
        $line = trim($line);
        array_push($students, $line);
    }

    //conclui o acesso ao ficheiro
    fclose($file);

    //remove o zero causado pela quebra de linha 
    array_pop($students);

    $daysStudents = array();
    $dh = array();

    for($i=0; $i<count($students); $i++){
        array_push($dh, $days[$i]);
        array_push($dh, $students[$i]);
        array_push($daysStudents, implode("_", $dh));
        $dh = [];
    }

    // Array para armazenar os contadores
    $counters = [];

    // Itera sobre a lista de estudantes
    foreach ($daysStudents as $dayStudent) {
        // Verifica se o estudante já existe no array de contadores
        if (isset($counters[$dayStudent])) {
            // Incrementa o contador do estudante existente
            $counters[$dayStudent]++;
        } else {
            // Cria um novo contador para o estudante
            $counters[$dayStudent] = 1;
        }
    }


    //inicia um array que irá juntar os dois atributos
    $l = array();

    //precorre pelos conjuntos de estudantes e de contadores, sendo este ultimo o nº de
    //envios realizados no respectivo estudante, e adiciona-as ao array $l, assim criando
    //o dado que irá ser adicionado ao conjunto de dados que irá ser utilizado para
    //conceber um grafico sobre o nº de envios efetuados em cada estudante
    foreach($counters as $daysStudents => $sendings){
        array_push($l, $daysStudents);
        array_push($l, $sendings);
        array_push($data->data, $l);
        //apaga o conteudo deste array para criar um novo dado 
        $l = [];
    }

    $sendDayStudents = $data->data;
    
    //echo json_encode($data);
?>
