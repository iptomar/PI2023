<?php
    if(!empty($_GET["volta"])){
        header("Location: Tabela_meses.php");
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

    button[type="submit"], input[type="submit"]{
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

<form method="get">
    <label for="email">E-mail:</label>
    <input type="text" name="email" id="email">

    <label for="mes">Mês:</label>
    <input type="text" name="mes" id="mes">

    <button type="submit">Filtrar</button>
    <input type="submit" name="volta" value="Voltar">
</form>

<table>
    <tr>
        <th>Email</th>
        <th>Mês</th>
        <th>Total de Registos</th>
    </tr>

    <?php
    // Ler o arquivo de registos
    $registros = file("registos.txt");

    // Inicializar o contador
    $contador = array();

    // Iterar sobre os registos
    foreach ($registros as $registro) {
        // Extrair os campos do registo
        $campos = explode(";", $registro);

        // Obter o email do utilizador
        $partes_email = explode(" ", trim($campos[4]));
        $email = $partes_email[0];

        // Obter o mes do registo
        $data_hora = strtotime($campos[1]);
        $mes = date('Y-m', $data_hora);

        // Incrementar o contador
        if (!isset($contador[$email])) {
            $contador[$email] = array();
        }

        if (!isset($contador[$email][$mes])) {
            $contador[$email][$mes] = 0;
        }

        $contador[$email][$mes]++;
    }

    // Verificar se o formulário foi enviado
    if ($_GET) {
        // Obter o e-mail e o mês selecionados
        $email = $_GET['email'];
        $mes = $_GET['mes'];

        // Verificar se os campos foram preenchidos
        if (!empty($email) && !empty($mes)) {
            // Filtrar os resultados pelo email e mes selecionados
            foreach ($contador[$email] as $mes_atual => $total_registros) {
                if ($mes_atual == $mes) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>$mes_atual</td>";
                    echo "<td>$total_registros</td>";
                    echo "</tr>";
                }
            }
        } elseif (!empty($email)) {
            // Filtrar os resultados pelo email selecionado
            foreach ($contador[$email] as $mes_atual => $total_registros) {
                echo "<tr>";
                echo "<td>$email</td>";
                echo "<td>$mes_atual</td>";
                echo "<td>$total_registros</td>";
                echo "</tr>";
            }
        } elseif (!empty($mes)) {
            // Filtrar os resultados pelo mes selecionado
            foreach ($contador as $email => $meses) {
                if (isset($meses[$mes])) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>$mes</td>";
                    echo "<td>{$meses[$mes]}</td>";
                    echo "</tr>";
                }
            }
        } else {
            // Mostrar todos os resultados
            foreach ($contador as $email => $meses) {
                foreach ($meses as $mes_atual => $total_registros) {
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>$mes_atual</td>";
                    echo "<td>$total_registros</td>";
                    echo "</tr>";
                }
            }
        }
    } else {
        // Mostrar todos os resultados
        foreach ($contador as $email => $meses) {
            foreach ($meses as $mes_atual => $total_registros) {
                echo "<tr>";
                echo "<td>$email</td>";
                echo "<td>$mes_atual</td>";
                echo "<td>$total_registros</td>";
                echo "</tr>";
            }
        }
    }
    ?>
</table>