<?php

    if(!empty($_POST["alunos"])){
        header("Location: Tabela_semanas_aluno.php");
    }

    if(!empty($_POST["volta"])){
        header("Location: index.php");
    }
?>
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

    form{
			max-width: 400px;
			margin: 50px auto;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
			background-color: #FFFFFF;
            text-align:center;
		}
		
		input[type="submit"]{
			background-color: #3498DB;
			color: #FFFFFF;
			font-size: 18px;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			margin-right: 10px;
			transition: background-color 0.3s ease;
		}
		
		input[type="submit"]:hover{
			background-color: #2980B9;
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
<form action="" method="post">
    <input type="submit" name="alunos" value="Alunos">
    <input type="submit" name="volta" value="Voltar">

</form>