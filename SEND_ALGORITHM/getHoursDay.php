<?php
    // impõe o ficheiro getDaysHoursSend.php como requerimento para permitir
    // que o getHoursDay.php utilize o codigo do ficheiro requerido
    require_once "getDaysHoursSend.php";

    $dayPost = $_POST["day"];
    $fileDay = fopen("selectedDay.csv", "w+");
    
    $day = ""; // Inicializa a variável com um valor padrão vazio
    
    if ($fileDay) {
        $day = fgets($fileDay);
        $day = trim($day);
    }
    
    if ($day != $dayPost) {
        fseek($fileDay, 0); // Volta para o início do arquivo
        fwrite($fileDay, $dayPost);
        $day = $dayPost;
    }
    
    fclose($fileDay);
    
    echo $day;
    // Cria uma nova stdClass
    $dataDay = new stdClass();

    // Atribui o título e inicializa o conjunto de dados
    $dataDay->title = "Volume of Sendings per hour of the day " . $day;
    $dataDay->data = array();

    // Cria as etiquetas
    $label = array();
    array_push($label, 'Hours');
    array_push($label, 'Sendings');

    // Adiciona as etiquetas ao conjunto de dados
    array_push($dataDay->data, $label);

    // Cria um array com todas as horas do dia
    $allHours = range(0, 23);

     //inicia um array que servirá para cirar os atributos
    $ld = array();

    // Percorre todas as horas do dia
    foreach ($allHours as $hour) {
        // Formata a hora com dois dígitos
        $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);

        // Verifica se existe envio para essa hora no conjunto de dados
        $sendDH = findSendHour($sendDayHours, $day, $formattedHour);

        // Se houver envio, adiciona a hora e o número de envios ao conjunto de dados
        if ($sendDH !== null) {
            array_push($ld, $formattedHour);
            array_push($ld, $sendDH[1]);
        } else {
            // Se não houver envio, atribui 0 para a hora não presente
            array_push($ld, $formattedHour);
            array_push($ld, 0);
        }

        //adiciona o dado ao conjunto de dados
        array_push($dataDay->data, $ld);

        //apaga o conteudo deste array para criar um novo dado 
        $ld = [];
    }

    //echo json_encode($dataDay);

    // Após construir o objeto $dataDay, converte-o para JSON
    $jsonData = json_encode($dataDay);


    if (isset($_SERVER['HTTP_REFERER'])) {
        $pagina_anterior = $_SERVER['HTTP_REFERER'];

        // Faça a condição desejada com a variável $pagina_anterior
        if ($pagina_anterior != "http://localhost/pi2023/SEND_ALGORITHM/SendChartHours.html") {
            //redireciona atomaticamente para a pagina SendChartHours.html
            header("Location: SendChartHours.html");
            // isto Certifica que script saiu após o redirecionamento
            exit(); 
        }
    }
    

    
    //Função para encontrar o envio de uma determinada hora no conjunto de dados.
    //Retorna o envio encontrado ou null se não for encontrado.

    function findSendHour($sendDayHours, $day, $hour)
    {
        foreach ($sendDayHours as $sendDH) {
            $sendDay = substr($sendDH[0], 0, 10);
            $sendHour = substr($sendDH[0], 11);
            if ($sendDay == $day && $sendHour == $hour) {
                return $sendDH;
            }
        }

        return null;
    }
?>
