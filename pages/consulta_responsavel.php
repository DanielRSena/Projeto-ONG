<?php
include ('../verificar_session.php');
include('../consulta/consulta_responsavel.php');
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">

</head>

<body>

  <br><br>

  <h1>Responsáveis</h1>

  <?php $responsaveis = mostrarResponsaveis(); ?>


  <div class="tableCentro">
    <table>
      <tr>
        <th>CPF</th>
        <th>Nome</th>
        <th>Data Nascimento</th>
        <th>Sexo</th>
        <th></th>
      </tr>
      <?php
      if (!empty($responsaveis)) {
        foreach ($responsaveis as $responsavel) {
            echo "<tr>";
            echo "<td>" . $responsavel['cpf'] . "</td>";
            echo "<td>" . $responsavel['nome'] . "</td>";
            echo "<td>" . $responsavel['email'] . "</td>";
            echo "<td>" . $responsavel['escolaridade'] . "</td>";
            echo "<td><a onclick='return confirm(\"Tem certeza que deseja deletar?\")' href='../deletes/deletar_responsavel.php?cpf=" . $responsavel['cpf'] . "'><i class='bi bi-trash'>Excluir</i>
</a></td>";
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