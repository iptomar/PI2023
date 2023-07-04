<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $selectedDate = $_GET['date'];

  //Abrir arquivo e recuperar informações referentes ao comando LOGOUT
  $myfile = fopen("../logIPRP.csv", "r");
  $data = array();
  while (!feof($myfile)) {
    $line = fgets($myfile);
    $fields = explode(";", $line);
    array_push($data, $fields);
  }
  fclose($myfile);

  $filteredData = array();
  foreach ($data as $row) {
    $dateTime = explode(" ", $row[1]);
    $date = $dateTime[0]; // Obtém a data no formato "2022-09-22"

    if (isset($row[3]) && trim($row[3]) === "LOGOUT" && trim($date) === $selectedDate) {
      $filteredData[] = $row;
    }
  }

  if (!empty($filteredData)) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Data e Hora</th><th>IP</th><th>Comando</th><th>Usuário</th><th>Status</th><th>Detalhes</th></tr>';
    foreach ($filteredData as $row) {
      echo '<tr>';
      foreach ($row as $cell) {
        echo '<td>' . $cell . '</td>';
      }
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo 'Nenhum logout encontrado para a data selecionada.';
  }
}
?>
