<?php
$myfile = fopen("../logIPRP.csv", "r");
$data = array();
while (!feof($myfile)) {
    $line = fgets($myfile);
    $fields = explode(";", $line);
    array_push($data, $fields);
}
fclose($myfile);

$filteredData = array();
if (isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];

    $tempData = array();
    foreach ($data as $row) {
        if (isset($row[1]) && trim($row[3]) === "LOGOUT" && trim(date("Y-m-d", strtotime($row[1]))) === $selectedDate) {
            $user = $row[4];
            if (!isset($tempData[$user])) {
                $tempData[$user] = 1;
            } else {
                $tempData[$user]++;
            }
        }
    }

    // Dividir o número de registros de cada usuário por 3
    $filteredData = array_map(function ($count) {
        return $count / 3;
    }, $tempData);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Logouts por Usuário</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <form method="POST" action="">
            <label for="selectedDate">Selecione uma data:</label>
            <input type="date" id="selectedDate" name="selectedDate">
            <button type="submit">Filtrar</button>
        </form>
    </header>

    <div id='selecionados'>
        <canvas id="logoutChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var labels = [];
            var data = [];

            <?php foreach ($filteredData as $user => $count): ?>
            labels.push("<?php echo $user; ?>");
            data.push(<?php echo $count; ?>);
            <?php endforeach; ?>

            var ctx = document.getElementById('logoutChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Número de Logouts por Usuário',
                        data: data,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...data) + 1, // Removido o limite superior do gráfico
                            stepSize: 1
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
