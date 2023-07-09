<?php

// verifica se o botão "volta" foi pressionado
if(!empty($_POST["volta"])){
    // se sim, redireciona o user para outra página
    header("Location: index.php");
}

if(!empty($_POST["alunos"])){
  header("Location: Tabela_dias_aluno.php");
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Registos Diários</title>
    <style type="text/css">
      body {
        background-color: #e8e8e8;
      }

      .form-table {
        overflow: hidden;
      }

      table {
        border: 1px solid #999;
        border-collapse: collapse;
        margin: 20px auto;
        font-family: Arial;
        font-size: 14px;
        color: #333;
        text-align: center;
        width: 50%;
        background-color: #fff;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      th, td {
        padding: 10px;
        border: 1px solid #999;
      }

      th {
        background-color: #2d3436;
        color: #fff;
        font-weight: bold;
      }

      tr {
        background-color: #fff;
        transition: background-color 0.3s ease;
      }

      tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      tr:hover {
        background-color: #dfe6e9;
      }

      form {
        float: left;
        width: 35%;
        margin: 20px 20px 20px 0;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-left: 475px;
      }

      input {
        padding: 5px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 90%;
        box-sizing: border-box;
      }

      input[type="submit"] {
        background-color: #2d3436;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      input[type="submit"]:hover {
        background-color: #444;
      }

      .error , p{
        float: left;
        color: #ff0000;
        font-weight: bold;
        margin-top: 70px;
        width: 650px;
        font-size: 20px;
      }
      
    </style>
  </head>
  <body>
    <?php
      // lê o arquivo txt com os registos diários e armazena as informações em um array $regByDay
      $file = file_get_contents('registos.txt');
      $lines = explode("\n", $file);
      $regByDay = [];
      foreach ($lines as $line) {
        if (empty($line)) {
          continue;
        }
        $values = explode(";", $line);
        $date = explode(" ", $values[1])[0];
        if (array_key_exists($date, $regByDay)) {
          $regByDay[$date] += 1;
        } else {
          $regByDay[$date] = 1;
        }
      }

      // verifica se o botão "busca" foi pressionado e, se sim, busca o número de registos para a data inserida pelo usuário e armazena o valor em uma variável $totalRegs
      if (!empty($_POST["busca"])) {
        $searchedDate = $_POST['searchedDate'];
        $totalRegs = (array_key_exists($searchedDate, $regByDay)) ? $regByDay[$searchedDate] : null;
      }
    ?>
    <div class="form-table">
      <!-- exibe um formulário onde o usuário pode inserir uma data para buscar o número de registos correspondente -->
      <form method="post">
          <label for="searchedDate">Digite uma data para buscar os registos:</label>
          <input type="date" name="searchedDate" id="searchedDate">
          <input type="submit" name="busca" value="Buscar"><br>
          <input type="submit" name="alunos" value="Alunos">
          <input type="submit" name="volta" value="Voltar">
      </form>
      <?php if (!empty($_POST["busca"])): ?>
          <?php if ($totalRegs === null): ?>
              <!-- exibe uma mensagem de erro se a data inserida pelo usuário não corresponder a nenhum registo -->
              <p class="error">Nenhum registo encontrado para a data inserida.</p>
          <?php else: ?>
              <!-- exibe o número de registos correspondente à data inserida pelo usuário -->
              <p>Total de registos encontrados para a data <?php echo $searchedDate; ?>: <?php echo $totalRegs; ?></p>
          <?php endif; ?>
      <?php endif; ?>
    </div>
    <!-- exibe uma tabela com os registos diários -->
    <table>
      <thead>
        <tr>
          <th>Data</th>
          <th>Número de Registos</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($regByDay as $date => $count): ?>
          <tr>
            <td><?php echo $date; ?></td>
            <td><?php echo $count; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </body>
</html>