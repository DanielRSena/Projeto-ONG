<?php
include ('../verificar_session.php');
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

    
    if(!$admin){
        View::abrir("../home.php");
    }

} else
    View::alert("Erro ao conectar com banco de dados");



?>

<main>
    <div class="dados" id='container'>
        <h2>Cadastro de usuário do sistema</h2>
        <form action="../cadastro/processar_cadastro_usuario.php" method="POST">

            <label for="nome-usuario">Nome do usuário:</label>
            <input type="text" id="nome-usuario" name="nome-usuario" required>

            <label for="login-usuario">Login:</label>
            <input type="text" id="login-usuario" name="login-usuario" required>

            <label for="senha-usuario">Senha:</label>
            <input type="password" id="senha-usuario" name="senha-usuario" required>

            <label for="senha2-usuario">Repetir senha:</label>
            <input type="password" id="senha2-usuario" name="senha2-usuario" required>

            <div class="gender-container">
                <label>Cadastrar usuário administrador?</label>
                <input type="radio" id="admin-sim" name="admin" value=1 required>
                <label for="admin-sim">Sim</label>
                <input type="radio" id="admin-nao" name="admin" value=0 required>
                <label for="admin-nao">Não</label>
            </div>
            <div class="button-container">
                <button type="submit" class="button">Cadastrar</button>
            </div>
        </form>
    </div>
</main>


<script>

</script>

<?php
require_once ('../view/footer.html');
?>