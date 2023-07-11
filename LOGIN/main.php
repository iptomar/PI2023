<!DOCTYPE html>
<html>
    <div class = "conteiner">
<head>
<title>Pagina Principal</title>
    <form action="grafico.php" method="post">
        <button type="submit" name="submit" value="verify" class="buttons">Grafico diario</button></form>
    
    <form action="graficosemanl.php" method="post">
        <button type="submit" name="submit" value="verify" class="buttons">Grafico semanal</button></form>

    <form action="graficomensal.php" method="post">
        <button type="submit" name="submit" value="verify" class="buttons">Grafico mensal</button></form>
    </head>
    </div>
<body>
<table>
    <thead>
        <link rel="stylesheet" href="css.css">
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
