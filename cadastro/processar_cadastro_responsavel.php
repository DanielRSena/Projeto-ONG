<?php
    require_once('../database.php');
    require_once('../view/view.php');

    $banco_dados = new Database;
    $pdo = $banco_dados->gerar_PDO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Recuperar os dados do formulário
        $nomeResponsavel = $_POST['nome-responsavel'];
        $cpfResponsavel = $_POST['cpf-responsavel'];
        $emailResponsavel = $_POST['email-responsavel'];
        $nivelEscolaridade = $_POST['nivel-escolaridade'];

        // Limpar o CPF removendo caracteres especiais (deixando apenas números)
        $cpfResponsavel = preg_replace("/[^0-9]/", "", $cpfResponsavel);

        // Preparar a consulta SQL para inserção
        $sql = "INSERT INTO responsáveis (nome, cpf, email, escolaridade) VALUES (:nome, :cpf, :email, :nivel_escolaridade)";

        // Preparar e executar a declaração usando PDO
        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nomeResponsavel);
        $stmt->bindParam(':cpf', $cpfResponsavel);
        $stmt->bindParam(':email', $emailResponsavel);
        $stmt->bindParam(':nivel_escolaridade', $nivelEscolaridade);

        // Executar a inserção
        try {
            $stmt->execute();
            View::alert("Responsável inserido com sucesso!");
        } catch (PDOException $e) {
            View::alert("Erro ao inserir responsável. Verifique se preencheu todos os campos e se as dependências estão cadastradas. " . $e->getMessage());
        }

    } else {
        header("Location: ../index.php");
        exit();
    }
?>