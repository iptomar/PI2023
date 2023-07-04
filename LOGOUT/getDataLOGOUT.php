<?php

  //Criar Objeto
  $data = new stdClass();
  $data->title = 'LOGOUT';
  $data->columns = array();

  $c1 = new stdClass();
  $c1->type = 'number';
  $c1->name = 'ID';

  $c2 = new stdClass();
  $c2->type = 'string';
  $c2->name = 'Data e Hora';

  $c3 = new stdClass();
  $c3->type = 'number';
  $c3->name = 'IP';

  $c4 = new stdClass();
  $c4->type = 'string';
  $c4->name = 'Comando';

  $c5 = new stdClass();
  $c5->type = 'string';
  $c5->name = 'Usuário';

  $c6 = new stdClass();
  $c6->type = 'string';
  $c6->name = 'Status';

  $c7 = new stdClass();
  $c7->type = 'string';
  $c7->name = 'Detalhes';

  array_push($data->columns, $c1);
  array_push($data->columns, $c2);
  array_push($data->columns, $c3);
  array_push($data->columns, $c4);
  array_push($data->columns, $c5);
  array_push($data->columns, $c6);
  array_push($data->columns, $c7);




  //Abrir arquivo e reccurar informações referentes ao comando LOGOUT
  $myfile = fopen("logout_amostra.csv", "r");
  $data = array();
  while (!feof($myfile)) {
      $line = fgets($myfile);
      $fields = explode(";", $line);
      array_push($data, $fields);
  }
  fclose($myfile);

  $filteredData = array();
  foreach ($data as $row) {
      if (isset($row[3]) && trim($row[3]) === "LOGOUT") {
          $filteredData[] = $row;
      }
  }
  //Area Reservada para transcrever os dados de cada linha para dentro do objeto
  
$data->lines = array();

foreach ($filteredData as $row) {
    $line = new stdClass();
    $line->cells = array();

    $cell1 = new stdClass();
    $cell1->value = $row[0];

    $cell2 = new stdClass();
    $cell2->value = $row[1];

    $cell3 = new stdClass();
    $cell3->value = $row[2];

    $cell4 = new stdClass();
    $cell4->value = $row[3];

    $cell5 = new stdClass();
    $cell5->value = $row[4];

    $cell6 = new stdClass();
    $cell6->value = $row[5];

    $cell7 = new stdClass();
    $cell7->value = $row[6];

    array_push($line->cells, $cell1);
    array_push($line->cells, $cell2);
    array_push($line->cells, $cell3);
    array_push($line->cells, $cell4);
    array_push($line->cells, $cell5);
    array_push($line->cells, $cell6);
    array_push($line->cells, $cell7);

    array_push($data->lines, $line);
}

echo json_encode($data);
?>
