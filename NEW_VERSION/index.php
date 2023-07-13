<!DOCTYPE html>
<html>
<head>
    <title>Atualizações do Algorithmi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #BEFFED;
            text-align: center;
        }

        h1 {
            margin-top: 50px;
        }

        .button-container {
            margin-top: 30px;
        }

        .button-container button {
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #3366CC;
            color: #ffffff;
            cursor: pointer;
            margin-right: 10px;
        }

        .button-container button:hover {
            background-color: #245ea8;
        }
    </style>
</head>
<body>
    <h1>Atualizações do Algorithmi</h1>

    <div class="button-container">
        <button onclick="window.location.href = 'graficodias.php';">Por Dia</button>
        <button onclick="window.location.href = 'graficosemanas.php';">Por Semana</button>
        <button onclick="window.location.href = 'graficohoras.php';">Por Hora</button>
    </div>
</body>
</html>