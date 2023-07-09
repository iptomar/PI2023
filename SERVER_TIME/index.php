<?php

    if(!empty($_POST["gD"])){
        header("Location: grafico_dias.php");
    }

    if(!empty($_POST["gS"])){
        header("Location: Grafico_semanas.php");
    }

    if(!empty($_POST["gM"])){
        header("Location: Grafico_meses.php");
    }

    if(!empty($_POST["tD"])){
        header("Location: Tabela_dias.php");
    }

    if(!empty($_POST["tS"])){
        header("Location: Tabela_semanas.php");
    }

    if(!empty($_POST["tM"])){
        header("Location: Tabela_meses.php");
    }

?>



<html>
    <title>Estatística</title>
    <style>
        body{
			background-color: #ECA881;
			font-family: Arial, sans-serif;
			font-size: 16px;
			color: #333;
            text-align:center;
		}
		
		form{
			max-width: 600px;
			margin: 0 auto;
			padding: 20px;
			border: 2px solid #333;
			border-radius: 5px;
			background-color: #FDF7E9;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            text-align:center;
            margin-top:20%;
		}
		
		input[type="submit"]{
			background-color: #333;
			color: #FFF;
			font-size: 16px;
			padding: 10px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s ease;
            margin-bottom:10px;
            margin-right:10px;
		}
		
		input[type="submit"]:hover{
			background-color: #111;
		}
    </style>

    <form action="index.php" method="post">

        <input type="submit" name="gD" value="Gráficos Diários">
        <input type="submit" name="gS" value="Gráficos Semanais">
        <input type="submit" name="gM" value="Gráficos Mensais"><br>
        <input type="submit" name="tD" value="Tabelas Diárias">
        <input type="submit" name="tS" value="Tabelas Semanais">
        <input type="submit" name="tM" value="Tabelas Mensais">

    </form>

    <h1>Estatística de utilização do Algorithmi</h1>

</html>

