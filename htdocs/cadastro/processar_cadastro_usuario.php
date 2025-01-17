<?php
// Incluir arquivo de configuração do banco de dados
require_once('../database.php'); // Substitua pelo seu arquivo de conexão
require_once('../view/view.php');

$banco_dados = new Database;
$pdo = $banco_dados->gerar_PDO();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário
    $nomeUsuario = $_POST['nome-usuario'];
    $loginUsuario = $_POST['login-usuario'];
    $senhaUsuario = $_POST['senha-usuario'];
    $senha2Usuario = $_POST['senha2-usuario'];
    $admin = $_POST['admin'];

    // Verificar se as senhas coincidem
    if ($senhaUsuario !== $senha2Usuario) {
        die("As senhas digitadas não coincidem.");
    }

    // Criar hash da senha
    $senhaHash = password_hash($senhaUsuario, PASSWORD_DEFAULT);

    // Preparar a consulta SQL para inserção
    $sql = "INSERT INTO usuários (nome, login, senha_hash, admin) 
            VALUES (:nome, :login, :senhaHash, :admin)";

    // Preparar e executar a declaração usando PDO
    $stmt = $pdo->prepare($sql);

    // Bind dos parâmetros
    $stmt->bindParam(':nome', $nomeUsuario);
    $stmt->bindParam(':login', $loginUsuario);
    $stmt->bindParam(':senhaHash', $senhaHash);
    $stmt->bindParam(':admin', $admin);

    // Executar a inserção
    try {
        $stmt->execute();
        View::alert("Usuário cadastrado com sucesso!");
    } catch (PDOException $e) {
        View::alert("Erro ao cadastrar usuário. Verifique se preencheu todos os campos corretamente. Erro: " . $e->getMessage());
    }

} else {
    // Redirecionar se o formulário não foi enviado
    header("Location: ../index.php");
    exit();
}
?>