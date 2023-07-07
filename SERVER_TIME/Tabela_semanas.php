
<style>
    table {
    border-collapse: collapse;
    width: 100%;
    border:2px solid black;
    font-family:verdana;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #4CAF50;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

@media screen and (max-width: 600px){
    table {
        display: block;
        overflow-x: auto;
    }
}
</style>
<table>
    <tr>
        <th>Semana</th>
        <th>Total de Registos</th>
    </tr>
    <?php
    $registros = file("registos.txt");

    $contador = 0;
    $semana_atual = null;

    foreach ($registros as $registro) {
        $campos = explode(";", $registro);
        $data_hora = strtotime($campos[1]);
        $semana = date('W', $data_hora);

        if ($semana_atual !== $semana) {
            if (!is_null($semana_atual)) {
                echo "<tr>";
                echo "<td>Semana $semana_atual</td>";
                echo "<td>$contador</td>";
                echo "</tr>";
            }
            $semana_atual = $semana;
            $contador = 0;
        }

        $contador++;
    }

    echo "<tr>";
    echo "<td>Semana $semana_atual</td>";
    echo "<td>$contador</td>";
    echo "</tr>";
    ?>
</table>