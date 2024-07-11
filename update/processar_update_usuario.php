<?php
  
    require_once('../database.php');
    require_once('../view/view.php');

    $banco_dados = new Database;
    $pdo = $banco_dados->gerar_PDO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Recuperar os dados do formulário
        $nomeUsuario = $_POST['nome-usuario'];
        $loginUsuario = $_POST['login-usuario'];
        $senhaUsuario = $_POST['senha-usuario'];
        $senha2Usuario = $_POST['senha2-usuario'];
        $admin = $_POST['admin'];

        if ($senhaUsuario !== $senha2Usuario) die("As senhas digitadas não coincidem.");
    
        $senhaHash = password_hash($senhaUsuario, PASSWORD_DEFAULT);

        // Preparar a consulta SQL para inserção
        $sql = "UPDATE usuários SET nome = :nome, senha_hash = :senhaHash, admin = :admin
            WHERE login = :login";

        $stmt = $pdo->prepare($sql); // Preparar e executar a declaração usando PDO

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nomeUsuario);
        $stmt->bindParam(':senhaHash', $senhaHash);
        $stmt->bindParam(':admin', $admin);
        $stmt->bindParam(':login', $loginUsuario);
        
        // Executar a inserção
        try {
            $stmt->execute();
            View::alert("Usuário atualizado com sucesso!");
            header("Location: ../pages/home.php");
        } catch (PDOException $e) {
            View::alert("Erro ao atualizar usuário. Verifique se preencheu todos os campos corretamente. Erro: " . $e->getMessage());
        }
    } 
    else {
        header("Location: ../pages/home.php");
        exit();
    }
?>