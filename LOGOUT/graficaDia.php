<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Logouts por Dia</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        $batchSize = 100000;
        $batchCount = 0;

        while (!feof($myfile)) {
            $line = fgets($myfile);
            $fields = explode(";", $line);
            $email = explode(" ", $fields[4])[0]; // Extrair o email do campo e remover o nome do usuário

            if (trim($email) === $selectedEmail) {
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
        $totalLogouts = $totalLogouts/3;
        if (empty($tempData)) {
            echo "Usuário não encontrado.";
        } else {
            $currentDate = $firstDate;
            while ($currentDate <= $lastDate) {
                $count = isset($tempData[$currentDate]) ? $tempData[$currentDate] / 3 : 0;
                $filteredData[$currentDate] = $count;
                $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
            }
        }
    }

    fclose($myfile);
    ?>

    <header>
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
                            text: '<?php echo $selectedEmail . " - Total de Logouts: " . $totalLogouts; ?>',
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
