<?php
include ('../verificar_session.php');
include('../consulta/consulta_psicopedagogo.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
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

<?php require_once ('modelos/header.html'); ?>

  <h1>psicopedagogos</h1>

<div class="container">

  <?php $psicopedagogos = mostrarPsicopedagogo();

  if (!empty($psicopedagogos)) {
  foreach ($psicopedagogos as $psico) {

    echo "<div class='divDados'>"; ?>

  <img src = "../mostrarImagem.php?cpf=<?php echo $psico['cpf'];?>&entidade=p" alt = 'Imagem extraída'> &nbsp;

    <?php  echo "<div><div>" . $psico['cpf'] . "</div> <br>";

      echo "<div>" . $psico['nome']. "</div> <br>";

    echo "<div>". $psico['num_registro'] . "</div> <br>";
      echo "</div></div> <br><br>";

  }
} 
else echo "Nenhum resultado encontrado.";

  ?>

  <?php require_once ('modelos/footer.html'); ?>