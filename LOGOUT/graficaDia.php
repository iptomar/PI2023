<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Logouts por Dia</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
    </style>
</head>
<body>
    <?php
    $myfile = fopen("logout_amostra.csv", "r");
    $filteredData = array();
$email = "";
$totalLogouts = 0; // Variável para armazenar o número total de logouts

if (isset($_POST['selectedEmail'])) {
    $selectedEmail = $_POST['selectedEmail'];

    $tempData = array();
    $firstDate = null;
    $lastDate = null;
    // Processar o arquivo em lotes de 1000 linhas
    $batchSize = 10000000000;
    $batchCount = 0;

    while (!feof($myfile)) {
        $line = fgets($myfile);
        $fields = explode(";", $line);
        $email = isset($fields[4]) ? explode(" ", $fields[4])[0] : '';
        $command = isset($fields[3]) ? $fields[3] : '';


        // Verificar se o comando é "LOGOUT" e o email corresponde ao selecionado
        if (trim($command) === "LOGOUT" && trim($email) === $selectedEmail) {
            $date = date("Y-m-d", strtotime($fields[1]));

            if ($firstDate === null || $date < $firstDate) {
                $firstDate = $date;
            }
            if ($lastDate === null || $date > $lastDate) {
                $lastDate = $date;
            }

            if (!isset($tempData[$date])) {
                $tempData[$date] = 1;
            } else {
                $tempData[$date]++;
            }

            $totalLogouts++; // Incrementar o número total de logouts
        }

        $batchCount++;
        if ($batchCount >= $batchSize) {
            break; // Interromper o processamento após atingir o tamanho do lote
        }
    }
    $totalLogouts = ceil($totalLogouts / 3);
    if (empty($tempData)) {
        echo "Usuário não encontrado.";
    } else {
        $currentDate = $firstDate;
        while ($currentDate <= $lastDate) {
            $count = isset($tempData[$currentDate]) ? $tempData[$currentDate] : 0;
            $filteredData[$currentDate] = ceil($count / 3);
            $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
        }
    }
} else {
    // Processar todos os registros sem filtrar por email
    $tempData = array();
    $firstDate = null;
    $lastDate = null;

    // Processar o arquivo em lotes de 1000 linhas
    $batchSize = 100000;
    $batchCount = 0;

    while (!feof($myfile)) {
        $line = fgets($myfile);
        $fields = explode(";", $line);

        // Verificar se o array $fields possui elementos suficientes
        if (count($fields) >= 4) {
            $command = $fields[3];

            // Verificar se o comando é "LOGOUT"
            if (trim($command) === "LOGOUT") {
                $date = date("Y-m-d", strtotime($fields[1]));

                if ($firstDate === null || $date < $firstDate) {
                    $firstDate = $date;
                }
                if ($lastDate === null || $date > $lastDate) {
                    $lastDate = $date;
                }

                if (!isset($tempData[$date])) {
                    $tempData[$date] = 1;
                } else {
                    $tempData[$date]++;
                }

                $totalLogouts++; // Incrementar o número total de logouts
            }
        }

        $batchCount++;
        if ($batchCount >= $batchSize) {
            break; // Interromper o processamento após atingir o tamanho do lote
        }
    }
    $totalLogouts = ceil($totalLogouts / 3);
    $currentDate = $firstDate;
    while ($currentDate <= $lastDate) {
        $count = isset($tempData[$currentDate]) ? $tempData[$currentDate] : 0;
        $filteredData[$currentDate] = $count / 3;
        $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
    }
}

fclose($myfile);
?>

<header>
    <a href="Inicio.html"><button>Retornar ao início</button></a>
    <form method="POST" action="">
        <label for="selectedEmail">Digite o email do usuário:</label>
        <input type="email" id="selectedEmail" name="selectedEmail">
        <button type="submit">Filtrar</button>
    </form>

</header>

<div id='selecionados'>
    <?php if (empty($filteredData)) : ?>
        <p><?php echo "Usuário não encontrado."; ?></p>
    <?php else : ?>
        <canvas id="logoutChart"></canvas>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var labels = [];
        var data = [];

        <?php foreach ($filteredData as $date => $count): ?>
        labels.push("<?php echo $date; ?>");
        data.push(<?php echo $count; ?>);
        <?php endforeach; ?>

        var ctx = document.getElementById('logoutChart').getContext('2d');
        var chart = new Chart(ctx, {
           type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Número de Logouts por Dia',
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: '<?php echo $selectedEmail ?? "Todos os Usuários"; ?> - Total de Logouts: <?php echo $totalLogouts; ?>',
                        padding: {
                            top: 10
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...data) + 1,
                        stepSize: 1
                    }
                }
            }
        });
    });
</script>
</body>
</html>
