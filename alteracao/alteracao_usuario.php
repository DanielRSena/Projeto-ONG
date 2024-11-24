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
    $sql = "SELECT * FROM usuários WHERE login = '$_GET[login]'";

    // Preparando a declaração
    $stmt = $conexao->prepare($sql);

    // Executando a consulta
    $stmt->execute();

    // Obtendo resultados
    $result = $stmt->get_result();
    $nome = $result->fetch_assoc()['nome'];

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

    <h2>Alteração de usuário</h2>
    <h3>Login:<?php echo "$_GET[login]"; ?></h3>
        <form action="../update/processar_update_usuario.php" method="post">

       
            <input type="hidden" id="login-usuario" name="login-usuario" value="<?php echo "$_GET[login]"; ?>">

            <label for="nome-usuario">Nome do usuário:</label>
            <input type="text" id="nome-usuario" name="nome-usuario" value="<?php echo "$nome"; ?>">



            <label for="senha-usuario">Senha:</label>
            <input type="password" id="senha-usuario" name="senha-usuario">

            <label for="senha2-usuario">Repetir senha:</label>
            <input type="password" id="senha2-usuario" name="senha2-usuario">

            <div class="gender-container">
                <label>Cadastrar usuário administrador?</label>
                <input type="radio" id="admin-sim" name="admin" value=1>
                <label for="admin-sim">Sim</label>
                <input type="radio" id="admin-nao" name="admin" value=0>
                <label for="admin-nao">Não</label>
            </div>
            <div class="button-container">
                <button type="submit" class="button">Alterar</button>
            </div>
        </form>
    </div>
</main>

<?php
    require_once ('../view/footer.html');
?>