<style>
    /* Estilos para a tabela */
    table {
        border-collapse: collapse;
        width: 100%;
        border: 2px solid black;
        font-family: verdana;
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

    @media screen and (max-width: 600px) {
        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
<table>
    <tr>
        <th>Email</th>
        <th>Semana</th>
        <th>Total de Registos</th>
    </tr>
    <?php
    // Ler o ficheiro de registros
    $registros = file("registos.txt");

    // Inicializar o contador
    $contador = array();
    $semana_atual = null;

    // Iterar sobre os registos
    foreach ($registros as $registro) {
        // Extrair os campos do registo
        $campos = explode(";", $registro);

        // Obter o email do utilizador
        $partes_email = explode(" ", trim($campos[4]));
        $email = $partes_email[0];

        // Obter a semana do registo
        $data_hora = strtotime($campos[1]);
        $semana = date('W', $data_hora);

        // Incrementar o contador
        if (!isset($contador[$email])) {
            $contador[$email] = array();
        }

        if (!isset($contador[$email][$semana])) {
            $contador[$email][$semana] = 0;
        }

        $contador[$email][$semana]++;
    }

    // Imprimir os resultados na tabela
    foreach ($contador as $email => $semanas) {
        foreach ($semanas as $semana => $total_registros) {
            echo "<tr>";
            echo "<td>$email</td>";
            echo "<td>Semana $semana</td>";
            echo "<td>$total_registros</td>";
            echo "</tr>";
        }
    }
    ?>
</table>