<?php
$myfile = fopen("logout_amostra.csv", "r");

$filteredData = array();
$email = "";
$totalLogouts = 0; // Variável para armazenar o número total de logouts

$selectedYear = isset($_POST['selectedYear']) ? $_POST['selectedYear'] : date("Y"); // Obtém o ano selecionado ou o ano atual

if (isset($_POST['selectedEmail'])) {
    $selectedEmail = $_POST['selectedEmail'];

    $tempData = array();
    $currentWeek = null;
    $currentYear = null;

    while (!feof($myfile)) {
        $line = fgets($myfile);
        $fields = explode(";", $line);
        $email = explode(" ", $fields[4])[0]; // Extrair o email do campo e remover o nome do usuário

        if (trim($email) === $selectedEmail) {
            $date = strtotime($fields[1]);

            $week = date("W", $date);
            $year = date("Y", $date);

            if ($year != $selectedYear) {
                continue; // Ignorar logouts de outros anos
            }

            $currentWeek = $week;
            $currentYear = $year;

            $key = $year . '-' . $week;

            if (!isset($tempData[$key])) {
                $tempData[$key] = 1;
            } else {
                $tempData[$key]++;
            }

            $totalLogouts++; // Incrementar o número total de logouts
        }
    }
    $totalLogouts = $totalLogouts / 3;

    if (empty($tempData)) {
        echo "Usuário não encontrado.";
    } else {
        $weeksInYear = getWeeksInYear($selectedYear);
        for ($week = 0; $week <= $weeksInYear; $week++) {
            $weekKey = $selectedYear . '-' . sprintf('%02d', $week);
            $weekLabel = 'Semana ' . $week;
            $logoutCount = isset($tempData[$weekKey]) ? $tempData[$weekKey] / 3 : 0;
            $filteredData[$weekKey] = array('label' => $weekLabel, 'count' => $logoutCount);
        }
    }
}

fclose($myfile);

function getWeeksInYear($year) {
    $date = new DateTime();
    $date->setISODate($year, 53);
    return ($date->format("W") === "53" ? 53 : 52);
}
?>

<!DOCTYPE html>
  <html>
    <head>
        <title>Gráfico de Logouts por Semana</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    </head>
    <body>
        <header>
            <form method="POST" action="">
                <label for="selectedEmail">Digite o email do usuário:</label>
                <input type="email" id="selectedEmail" name="selectedEmail">
                <label for="selectedYear">Selecione o ano:</label>
                <select id="selectedYear" name="selectedYear">
                  <?php
    $currentYear = date("Y");
    for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
      $selected = ($year == $selectedYear) ? 'selected' : '';
      echo "<option value='$year' $selected>$year</option>";
    }
    ?>
    </select>
    <button type="submit">Filtrar</button>
    </form>
    <a href="Inicio.html"><button>Retornar ao inicio</button></a>
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

    <?php foreach ($filteredData as $weekKey => $weekData): ?>
    labels.push("<?php echo $weekData['label']; ?>");
    data.push(<?php echo $weekData['count']; ?>);
    <?php endforeach; ?>

    var ctx = document.getElementById('logoutChart').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
          label: 'Número de Logouts por Semana',
          data: data,
          backgroundColor: 'rgba(0, 123, 255, 0.7)',
          borderColor: 'rgba(0, 123, 255, 1)',
          borderWidth: 1
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
              suggestedMax: Math.max(...data) > 0 ? Math.max(...data) + 1 : 10,
              stepSize: 1
          },
          x: {
              minBarLength: 1,
              ticks: {
                  callback: function(value, index, values) {
                      return  data[index] +'\n'+ value  ;
                  }
              }
          }
      }
    }
    });
    });
    </script>
  </body>
</html>
