<?php

if(!empty($_POST["volta"])){
	header("Location: index.php");
}

if(!empty($_POST["alunos"])){
	header("Location: Tabela_meses_aluno.php");
}

// Array com os nomes dos meses em português
$nomes_meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

// Abre o arquivo de registros
$file = fopen("registos.txt", "r");

// Cria um array para armazenar o total de registros por mês
$registros_por_mes = array();

// Loop através de cada registro do arquivo
while (!feof($file)) {
    // Lê a linha atual do arquivo
    $linha = fgets($file);
    
    // Separa os campos da linha em um array
    $campos = explode(";", $linha);
    
    // Extrai a data do registro
    $data = strtotime($campos[1]);
    $mes = date('n', $data);
    
    // Se o mês ainda não foi adicionado ao array, adiciona com valor zero
    if (!isset($registros_por_mes[$mes])) {
        $registros_por_mes[$mes] = 0;
    }
    
    // Incrementa o total de registros do mês atual
    $registros_por_mes[$mes]++;
}

// Fecha o arquivo
fclose($file);

// Cria uma tabela para mostrar os totais por mês
echo "<table>";
echo "<tr><th>Mês</th><th>Total de Registos</th></tr>";
foreach ($registros_por_mes as $mes => $total) {
    $nome_mes = $nomes_meses[$mes];
    echo "<tr><td>" . ucfirst($nome_mes) . "</td><td>$total</td></tr>";
}
echo "</table>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registos Mensais</title>
	<meta charset="utf-8">
	<style type="text/css">
		body {
			background-color: #f1f1f1;
			font-family: Arial, sans-serif;
		}
		h1 {
			text-align: center;
			color: #333;
		}
		table {
			border-collapse: collapse;
			margin: 0 auto;
			background-color: #fff;
			box-shadow: 0px 0px 10px #ccc;
            width:400px;
            height:400px;
		}
		td, th {
			padding: 8px;
			border: 1px solid #ddd;
			text-align: left;
		}
		th {
			background-color: #3090C7;
			color: #fff;
		}
		tr:nth-child(odd) {
			background-color: #f2f2f2;
		}

		form{
			max-width: 360px;
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
	</style>
</head>
<form action="" method="post">
    <input type="submit" name="alunos" value="Alunos">
    <input type="submit" name="volta" value="Voltar">

</form>