<?php
    //cria uma nova standard class
    $data = new stdClass();

    //atribui o titulo e inicializa o conjunto dos dados
    $data->title = 'Sending per day of the student ...';
    $data->data = array();

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
    $ds = array();

    for($i=0; $i<count($students); $i++){
        array_push($ds, $days[$i]);
        array_push($ds, $students[$i]);
        array_push($daysStudents, implode("_", $ds));
        $ds = [];
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

    $sendStudentDays = $data->data;

    //lista de todos os dias entre o primeiro dia registrado e o ultimo
    $allDays = $days;

    // remover duplicações dos dias
    $allDays = array_unique($allDays);

    // Obter o primeiro e o último dia
    $firstDay = reset($allDays);
    $lastDay = end($allDays);

    // Criar o array para armazenar os dias ausentes
    $missingDays = [];

    // Inicializar a data atual com o primeiro dia
    $currentDay = new DateTime($firstDay);

    // Loop para verificar e adicionar os dias ausentes
    while ($currentDay->format('Y-m-d') <= $lastDay) {
        $currentDate = $currentDay->format('Y-m-d');
        
        if (!in_array($currentDate, $allDays)) {
            $missingDays[] = $currentDate;
        }
        
        $currentDay->modify('+1 day'); // Avançar para o próximo dia
    }

    // lista de todos os estudantes que foram registrados 
    $allStudents = $students;

    // remover duplicações dos estudantes
    $allStudents = array_unique($allStudents)
?>
