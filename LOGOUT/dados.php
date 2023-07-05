<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $selectedDate = $_GET['date'];

  // Abrir arquivo e recuperar informações referentes ao comando LOGOUT
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
    $dateTime = explode(" ", $row[1]);
    $date = $dateTime[0]; // Obtém a data no formato "2022-09-22"

    if (isset($row[3]) && trim($row[3]) === "LOGOUT" && trim($date) === $selectedDate) {
      $filteredData[] = trim($row[4]); // Obtém o e-mail do usuário que fez logout
    }
  }

  $logoutCounts = array_count_values($filteredData); // Conta o número de ocorrências de cada e-mail

  $chartData = array();
  foreach ($logoutCounts as $usuario => $contagem) {
    $item = array(
      'usuario' => $usuario,
      'contagem' => $contagem
    );
    $chartData[] = $item;
  }

  echo json_encode($chartData);
}
?>
