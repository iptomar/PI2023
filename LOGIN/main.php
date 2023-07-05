<!DOCTYPE html>
<html>
<body>
<table>
    <thead>
        <tr>
            <th>Date-Time</th>
            <th>IP</th>
            <th>Email</th>
            <th>Nome</th>
        </tr>
    </thead>
    <tbody>



<?php

// Ler o arquivo de csv
$csvFile = fopen('logIPRP.csv', 'r');
if ($csvFile === false) {
    die("Erro ao abrir o arquivo de log");
}
$headers = fgetcsv($csvFile);
    
while (($row = fgetcsv($csvFile)) !== false) {
    $linevalues = array_map("trim", explode(";", $row[0]));
    $date = $linevalues[1]??'';
    $ip = $linevalues[2]??'';
    $email = $linevalues[4]??'';
    $separa = explode(" ", $email);
    $maill = $separa[0]??'';
    $nome = (count($separa) >= 2) ? $separa[1] : '' ;

                echo "<tr>";
                echo "<td>" . $date . "</td>";
                echo "<td>" . $ip . "</td>";
                echo "<td>" . $maill . "</td>";
                echo "<td>" . $nome . "</td>";
                echo "</tr>";
}
fclose($csvFile);

?>




</tbody>   
</table>
