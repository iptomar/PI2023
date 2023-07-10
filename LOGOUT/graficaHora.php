<?php
// Função para obter o email com base nos campos do CSV
function getEmailFromFields($fields)
{
    if (isset($fields[4])) {
        $emailParts = explode(" ", $fields[4]);
        return $emailParts[0];
    } else {
        return '';
    }
}

// Verificar se foi enviado um usuário através do parâmetro GET
if (isset($_GET['user'])) {
    $selectedUser = $_GET['user'];
} else {
    $selectedUser = ''; // Define o usuário como vazio se nenhum foi fornecido
}

// Abrir o arquivo CSV para leitura
$file = fopen("logout_amostra.csv", "r");

// Extrair o cabeçalho das colunas
$header = fgetcsv($file, 0, ";");

// Criar um array vazio para armazenar as contagens
$counts = array_fill(0, 24, 0); // Armazena as contagens para cada hora
$totalLogouts = 0; // Armazena o valor total de logouts

// Ler cada linha do arquivo
while (($line = fgetcsv($file, 0, ";")) !== false) {
    // Obter o email com base nos campos do CSV
    $email = getEmailFromFields($line);

    // Verificar se o usuário está selecionado e filtrar os dados com base no email
    if ($selectedUser !== '' && $email !== $selectedUser) {
        continue;
    }

    // Separar a data em ano, mês, dia e hora
    $dateTime = new DateTime($line[1]);
    $hora = $dateTime->format('H');

    // Incrementar a contagem correspondente
    $counts[(int)$hora]++;

    // Incrementar o valor total de logouts
    $totalLogouts++;
}
$totalLogouts = ceil($totalLogouts / 3);
// Dividir por 3 o número de logouts de cada hora
foreach ($counts as &$count) {
    $count = ceil($count / 3);
}

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
        <a href="Inicio.html"><button>Retornar ao início</button></a>
        <form method="GET">
            <input type="text" name="user" placeholder="Insira o usuário">
            <button type="submit">Filtrar</button>
        </form>
        <p><?php echo "Total de Logouts: " . $totalLogouts; ?></p>
        <canvas id="logoutChart"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var labels = []; // Armazena os rótulos (horas)
            var data = <?php echo json_encode(array_values($counts)); ?>; // Usar apenas os valores do array $counts
            var user = "<?php echo $selectedUser; ?>"; // Obter o usuário selecionado

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
                        label: 'Número de Logouts por Hora' + (user ? ' - Usuário: ' + user : ''),
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
