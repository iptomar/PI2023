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

// Cria um array com todas as datas
$allDays = array_column($sendStudentDay, 0);

// Remove os duplicados do array de datas
$allDays = array_unique($allDays);

// Ordena as datas em ordem crescente
sort($allDays);

// Inicializa um array para armazenar os contadores de envios por dia
$counters = [];

// Percorre todas as datas
foreach ($allDays as $day) {
    $counters[$day] = 0; // Inicializa o contador com 0 para cada dia
}

// Conta os envios por dia
foreach ($sendStudentDay as $sendDS) {
    $sendDay = substr($sendDS[0], 0, 10);
    $sendStudent = substr($sendDS[0], 12);
    if ($sendStudent == $student) {
        $counters[$sendDay] += $sendDS[1];
    }
}

// Inicializa um array que servirá para criar os atributos
$ls = array();

// Percorre pelos dias e contadores, adicionando-os ao conjunto de dados
foreach ($counters as $day => $sendings) {
    array_push($ls, $day);
    array_push($ls, $sendings);
    array_push($dataStudent->data, $ls);
    $ls = [];
}

echo json_encode($dataStudent);
?>
