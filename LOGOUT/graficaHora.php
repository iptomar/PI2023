<?php
// Abrir o arquivo CSV para leitura
$file = fopen("logout_amostra.csv", "r");

// Extrair o cabeçalho das colunas
$header = fgetcsv($file, 0, ";");

// Criar um array vazio para armazenar as contagens
$counts = array(); // Armazena as contagens

// Ler cada linha do arquivo
while (($line = fgetcsv($file, 0, ";")) !== false) {
    // Separar a data em ano, mês, dia e hora
    $dateTime = new DateTime($line[1]);
    $hora = $dateTime->format('H');

    // Incrementar a contagem correspondente
    if (!isset($counts[$hora])) {
        $counts[$hora] =ceil( 1 /3);
    } else {
        $counts[$hora]+= ceil( 1/3);
    }
}

ksort($counts); // Ordenar o array $counts pelas chaves

fclose($file);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Logouts por Hora</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
</head>
<body>
    <div id='selecionados'>
        <a href="Inicio.html"><button>Retornar ao inicio</button></a>
        <canvas id="logoutChart"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var labels = []; // Armazena os rótulos (horas)
            var data = <?php echo json_encode(array_values($counts)); ?>; // Usar apenas os valores do array $counts

            // Preencher os rótulos com as horas de 00 a 23
            for (var i = 0; i < 24; i++) {
                var hour = i.toString().padStart(2, '0'); // Formatar o número da hora com zero à esquerda
                labels.push(hour);
            }

            var ctx = document.getElementById('logoutChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Definir os rótulos no eixo x
                    datasets: [{
                        label: 'Número de Logouts por Hora',
                        data: data, // Definir os dados no eixo y
                        backgroundColor: 'rgba(0, 123, 255, 0.7)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...data) + 1, // Definir o valor máximo sugerido para o eixo y
                            stepSize: 1 // Definir o tamanho do intervalo entre os valores do eixo y
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
