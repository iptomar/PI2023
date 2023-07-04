<?php
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
    <button><label> Apresentar Tabela com todos os registros de LOGOUT</label></button>
    <button><label> Apresentar Tabela com todos os registros de LOGOUT</label></button>
  </header>
  <div id='registros'>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data e Hora</th>
                <th>IP</th>
                <th>Comando</th>
                <th>Usuário</th>
                <th>Status</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredData as $row): ?>
                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td><?php echo $row[3]; ?></td>
                    <td><?php echo $row[4]; ?></td>
                    <td><?php echo $row[5]; ?></td>
                    <td><?php echo $row[6]; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
  <div id= 'selecionados'></div>
</body>
</html>
