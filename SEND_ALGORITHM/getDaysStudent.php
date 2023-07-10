<?php
    // Impõe o ficheiro getStudentsDaysSend.php como requerimento para permitir
    // que o getHoursDay.php utilize o código do ficheiro requerido
    require_once "getStudentsDaysSend.php";

    // Obtenha a data selecionada como parâmetro
    $student = $_GET['student'];

    // Cria uma nova stdClass
    $dataStudent = new stdClass();

    // Atribui o título e inicializa o conjunto de dados
    $dataStudent->title = "Sendings per Day of the student " . $student;
    $dataStudent->data = array();

    
    // Cria as etiquetas
    $label = array();
    array_push($label, 'Days');
    array_push($label, 'Sendings');

    // Adiciona as etiquetas ao conjunto de dados
    array_push($dataStudent->data, $label);

    // Inicializa um array para armazenar os contadores de envios por dia
    $countersDay = [];


    // Percorre todas as datas
    foreach ($allDays as $day) {
        $countersDay[$day] = 0; // Inicializa o contador com 0 para cada dia
    }

    // Inicializa um array que servirá para criar os atributos
    $ls = array();

    // Conta os envios por dia
    foreach ($allDays as $day) {
    $sendDS = findSendStudent($sendStudentDays, $day, $student);

    // Se houver envio, adiciona dia e o número de envios ao conjunto de dados
    if ($sendDS !== null) {
        array_push($ls, $day);
        array_push($ls, $sendDS[1]);
    } else {
        // Se não houver envio, atribui 0 para ao dia não presente
        array_push($ls, $day);
        array_push($ls, 0);
    }

    //adiciona o dado ao conjunto de dados
    array_push($dataStudent->data, $ls);

    //apaga o conteudo deste array para criar um novo dado 
    $ls = [];
    }

    // loop para verificar e adicionar os dias ausentes e inicia-los a zero 
    foreach ($missingDays as $missDay) {
        $found = false;
        foreach ($dataStudent->data as $existingData) {
            if ($existingData[0] == $missDay) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $missingDayData = [$missDay, 0];
            array_push($dataStudent->data, $missingDayData);
        }
    }
    
    // odernar os dados de acordo com as datas
    usort($dataStudent->data, function ($a, $b) {
        return strtotime($a[0]) - strtotime($b[0]);
    });

    // faz o encode do json para que os dados possam ser lidos
    // para que seja possivel criar o grafico
    echo json_encode($dataStudent);

    //Função para encontrar o envio de uma determinada hora no conjunto de dados.
    //Retorna o envio encontrado ou null se não for encontrado.
    function findSendStudent($sendStudentDays, $day, $student){
        foreach ($sendStudentDays as $sendDS) {
            $sendDay = substr($sendDS[0], 0, 10);
            $sendStudent = substr($sendDS[0], 11);
            if ($sendDay == $day && $sendStudent == $student) {
                return $sendDS;
            }
        }

        return null;
    }
?>
