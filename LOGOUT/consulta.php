<?php
$myfile = fopen("../logIPRP.csv", "r");
$data = array();
while (!feof($myfile)) {
    $line = fgets($myfile);
    $fields = explode(";", $line);
    array_push($data, $fields);
}
fclose($myfile);

$filteredData = array();
if (isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];

    $tempData = array();
    $lineCount = 0;
    foreach ($data as $row) {
        if (isset($row[1]) && trim($row[3]) === "LOGOUT" && trim(date("Y-m-d", strtotime($row[1]))) === $selectedDate) {
            $status = trim($row[5]);

            if ($status === "connected[1]" || $status === "processed[1]" || $status === "service done![1]") {
                if (!isset($tempData['details'])) {
                    $tempData['date'] = $row[1];
                    $tempData['ip'] = $row[2];
                    $tempData['users'] = $row[4];
                    $tempData['details'] = $row[6];
                } else {
                    $tempData['details'] .= ', ' . $row[6];
                }
            } else {
                // Se o status for diferente, adiciona a linha atual ao resultado final
                if (!empty($tempData)) {
                    $filteredData[] = $tempData;
                    $tempData = array();
                }

                $filteredData[] = array(
                    'date' => $row[1],
                    'ip' => $row[2],
                    'users' => $row[4],
                    'details' => $row[6]
                );
            }

            $lineCount++;

            if ($lineCount % 3 === 0 && !empty($tempData)) {
                $filteredData[] = $tempData;
                $tempData = array();
            }
        }
    }

    // Adicionar a última linha ao resultado final
    if (!empty($tempData)) {
        $filteredData[] = $tempData;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabela de Registros de Logout</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        #registros{

        }
    </style>
</head>
<body>
  <header>
    <form method="POST" action="">
      <label for="selectedDate">Selecione uma data:</label>
      <input type="date" id="selectedDate" name="selectedDate">
      <button type="submit">Filtrar</button>
    </form>
  </header>
  <div id='registros'>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>IP</th>
                <th>Usuários</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredData as $index => $row): ?>
                <?php if (is_array($row)): ?>
                    <?php if ($index % 3 === 0): ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['ip']; ?></td>
                            <td><?php echo $row['users']; ?></td>
                            <td><?php echo $row['details']; ?></td>
                        </tr>
                    <?php else: ?>
                        <?php $filteredData[$index - 1]['details'] .= ', ' . $row['details']; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['ip']; ?></td>
                        <td><?php echo $row['users']; ?></td>
                        <td><?php echo $row['details']; ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
  <div id='selecionados'></div>
</body>
</html>
