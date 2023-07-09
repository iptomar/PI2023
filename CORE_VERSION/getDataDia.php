<?php
$data = new stdClass();
$data->title = 'Entradas por Dia';
$data->data = array();

$label = array();
array_push($label, 'dias');
array_push($label, 'entradas');
array_push($data->data, $label);

$file = fopen("teste.csv", "r");
$days = array();
while (!feof($file)) {
    $line = fgets($file);
    array_push($days, $line);
}
fclose($file);

array_pop($days);
//array_shift($days);
$counters = [];
// Itera sobre a lista de datas
foreach ($days as $day) {
    // Extrai apenas a parte da data sem as horas
    $dateOnly = substr($day, 0, 10);

    // Verifica se a data já existe no array de contadores
    if (isset($counters[$dateOnly])) {
        // Incrementa o contador dos dias
        $counters[$dateOnly]++;
    } else {
        // contador para o dia x
        $counters[$dateOnly] = 1;
    }
}
$l = array();
foreach ($counters as $day => $count) {
    array_push($l, $day);
    array_push($l, $count);
    array_push($data->data, $l);
    $l = [];
}
echo json_encode($data);
//$encodedData = json_encode($data);
//$encodedDataWithLineBreaks = implode("\n", explode(",", $encodedData));
//echo $encodedDataWithLineBreaks;
?>
