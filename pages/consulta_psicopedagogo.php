<?php
include ('../verificar_session.php');
include('../consulta/consulta_psicopedagogo.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../view/header.html');
require_once('../database.php');


$banco_dados = new Database;
$conexao = $banco_dados->abrir_conexao();

if ($conexao !== false) {
    $usuario = $_SESSION['user'];

    // Consulta SQL
    $sql = "SELECT * FROM usuários WHERE login = ? AND admin = TRUE";

    // Preparando a declaração
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $usuario);

    // Executando a consulta
    $stmt->execute();

    // Obtendo resultados
    $result = $stmt->get_result();

    // Verificando se encontrou algum resultado
    if ($result->num_rows > 0) {
        // Usuário possui a coluna admin como TRUE
        $admin = true;
    } else {
        // Usuário não é administrador
        $admin = false;
    }

    // Fechando a declaração e a conexão
    $stmt->close();
    $conexao->close();
} else
    View::alert("Erro ao conectar com banco de dados");
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../view/style.css">
</head>

<body>

  <br><br>

  <h1>psicopedagogos</h1>

  <?php $psicopedagogos = mostrarPsicopedagogo(); ?>


  <div class="tableCentro">
    <table>
      <tr>
      <th>Número de Registro</th>
        <th>Nome</th>
        <th>CPF</th>
      </tr>
      <?php
      if (!empty($psicopedagogos)) {
        foreach ($psicopedagogos as $psicopedagogo) {
          echo "<tr>";
          echo "<td>" . $psicopedagogo['num_registro'] . "</td>";
          echo "<td>" . $psicopedagogo['nome'] . "</td>";
          echo "<td>" . $psicopedagogo['cpf'] . "</td>";
          echo "</tr>";
        }
      } else {
        echo "Nenhum resultado encontrado.";
      }
      ?>
    </table>
  </div>

  <?php
    require_once ('../view/footer.html');
    ?>

</body>

</html>