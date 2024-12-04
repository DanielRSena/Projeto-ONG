<?php
  include('../verificar_session.php');
  include('../consulta/consulta_usuario.php');
  verificar('../view/view.php', 'login.php');
  require_once('../view/view.php');
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

<?php require_once('modelos/header.html'); ?>

  <h1>usuarios</h1>

  <?php $usuarios = mostrarUsuarios(); ?>

  <div class="tableCentro">
    <table>
      <tr>
        <th>Login</th>
        <th>Senha</th>
        <th>Tipo de usuário</th>
        <th>Cpf</th> 
        <th></th>
      </tr>
      <?php
      if (!empty($usuarios)) {
        foreach ($usuarios as $usuario) {
          echo "<tr>";
          echo "<td>" . $usuario['login'] . "</td>";
          echo "<td>" . $usuario['senha'] . "</td>";

          if ($usuario['admin'] == 0) echo "<td> Comum</td>";
          else echo "<td> Administrador</td>";

          echo "<td>" . $usuario['cpf'] . "</td>";

          echo "<td><a href='../alteracao/alteracao_usuario.php?login=" . $usuario['login'] . "'> <i class='fas fa-pencil-alt'></i>&nbsp; Alterar</a></td>";

          echo "</tr>";
        }
      } else {
        echo "Nenhum resultado encontrado.";
      }
      ?>
    </table>
  </div>

  <?php require_once('modelos/footer.html'); ?>