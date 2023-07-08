<?php
    if(!empty($_GET["volta"])){
        header("Location: Tabela_semanas.php");
    }
?>
<style>

    /* Estilos para o formulário */
    form {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"] {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 10px;
    }

    button[type="submit"],input[type="submit"]{
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 12px;
        cursor: pointer;
    }
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

<!-- Formulário de Filtro -->
<form method="get">
    <label for="email">E-mail:</label>
    <input type="text" name="email" id="email">

    <label for="semana">Semana:</label>
    <input type="text" name="semana" id="semana">

    <button type="submit">Filtrar</button>
    <input type="submit" name="volta" value="Voltar">
</form>

<table>
    <tr>
        <th>Email</th>
        <th>Semana</th>
        <th>Total de Registos</th>
    </tr>
    <?php
    // Ler o arquivo de registos
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

    // Verificar se o formulário foi enviado
    if ($_GET) {
        // Obter o e-mail e a semana selecionados
        $email = $_GET['email'];
        $semana = $_GET['semana'];

        // Verificar se os campos foram preenchidos
        if (!empty($email) && !empty($semana)) {
            // Filtrar os resultados pelo email e semana selecionados
            foreach ($contador[$email] as $semana_atual => $total_registros) {
                if ($semana_atual == $semana) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>Semana $semana_atual</td>";
                    echo "<td>$total_registros</td>";
                    echo "</tr>";
                }
            }
        } elseif (!empty($email)) {
            // Filtrar os resultados pelo email selecionado
            foreach ($contador[$email] as $semana_atual => $total_registros) {
                echo "<tr>";
                echo "<td>$email</td>";
                echo "<td>Semana $semana_atual</td>";
                echo "<td>$total_registros</td>";
                echo "</tr>";
            }
        } elseif (!empty($semana)) {
            // Filtrar os resultados pela semana selecionada
            foreach ($contador as $email => $semanas) {
                if (isset($semanas[$semana])) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>Semana $semana</td>";
                    echo "<td>{$semanas[$semana]}</td>";
                    echo "</tr>";
                }
            }
        } else {
            // Mostrar todos os resultados
            foreach ($contador as $email => $semanas) {
                foreach ($semanas as $semana => $total_registros) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>Semana $semana</td>";
                    echo "<td>$total_registros</td>";
                    echo "</tr>";
                }
            }
        }
    } else {
        // Mostrar todos os resultados
        foreach ($contador as $email => $semanas) {
            foreach ($semanas as $semana => $total_registros) {
                echo "<tr>";
                echo "<td>$email</td>";
                echo "<td>Semana $semana</td>";
                echo "<td>$total_registros</td>";
                echo "</tr>";
            }
        }
    }
    ?>
</table>