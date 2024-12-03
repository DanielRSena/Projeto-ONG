<?php
// Incluir arquivo de configuração do banco de dados
require_once('../database.php'); // Substitua pelo seu arquivo de conexão

$banco_dados = new Database;
$pdo = $banco_dados->gerar_PDO();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário
    $data_inicio = $_POST['data_aplicacao'];
    $cpf_assistido = $_POST['cpf_assistido'];
    $cpf_psicopedagogo = $_POST['cpf_psicopedagogo'];

    $cpf_assistido = preg_replace('/\D/', '', $cpf_assistido);
    $cpf_psicopedagogo = preg_replace('/\D/', '', $cpf_psicopedagogo);

    // Preparar a consulta SQL para inserção da avaliação
    $sql_insert_avaliacao = "INSERT INTO avaliações (cpf_assistido, cpf_psico, data_inicio_avaliacao, data_teste) 
                             VALUES (:cpf_assistido, :cpf_psicopedagogo, :data_inicio_avaliacao, :data_teste)";

    // Preparar e executar a declaração usando PDO para inserir a avaliação
    $stmt_insert_avaliacao = $pdo->prepare($sql_insert_avaliacao);

    // Bind dos parâmetros para inserção da avaliação
    $stmt_insert_avaliacao->bindParam(':cpf_assistido', $cpf_assistido);
    $stmt_insert_avaliacao->bindParam(':cpf_psicopedagogo', $cpf_psicopedagogo);
    $stmt_insert_avaliacao->bindParam(':data_inicio_avaliacao', $data_inicio);
    $stmt_insert_avaliacao->bindParam(':data_teste', $data_inicio);

    // Executar a inserção da avaliação
    try {
        $pdo->beginTransaction();
    
        $stmt_insert_avaliacao->execute();
    
        // Recuperar o ID gerado da avaliação
        $id_avaliacao = $pdo->lastInsertId();
    
        // Inserir o id_avaliacao na tabela avaliacoes_nao_finalizadas
        $sql_insert_avaliacao_nao_finalizada = "INSERT INTO avaliacoes_nao_finalizadas (id_avaliacao) VALUES (:id_avaliacao)";
        $stmt_insert_avaliacao_nao_finalizada = $pdo->prepare($sql_insert_avaliacao_nao_finalizada);
        $stmt_insert_avaliacao_nao_finalizada->bindParam(':id_avaliacao', $id_avaliacao);
        $stmt_insert_avaliacao_nao_finalizada->execute();
    
        // Preparar a consulta SQL para inserção das respostas das questões
        $sql_insert_respostas = "INSERT INTO testes_questoes (id_avaliacao, id_questao, resposta) 
                                 VALUES (:id_avaliacao, :id_questao, :resposta)";
    
        $stmt_insert_respostas = $pdo->prepare($sql_insert_respostas);
    
        // Iterar sobre as respostas das questões do formulário
        foreach ($_POST as $key => $value) {
            // Verificar se é uma resposta de questão (formato resposta_{id_questao})
            if (strpos($key, 'resposta_') !== false) {
                $id_questao = str_replace('resposta_', '', $key);
                $resposta = $value;
    
                // Bind dos parâmetros para inserção da resposta
                $stmt_insert_respostas->bindParam(':id_avaliacao', $id_avaliacao);
                $stmt_insert_respostas->bindParam(':id_questao', $id_questao);
                $stmt_insert_respostas->bindParam(':resposta', $resposta);
    
                // Executar a inserção da resposta
                $stmt_insert_respostas->execute();
            }
        }
    
        $pdo->commit();
    
        // Redirecionar para teste.php com o ID como parâmetro
        echo 'Teste cadastrado com sucesso! Redirecionando para finalizar avaliação...';
        sleep(5);
        View::abrir("../pages/finalizar_avaliacao_selecionar.php");
        exit();
    
    } catch (PDOException $e) {
        // Caso ocorra algum erro, desfaz as alterações
        $pdo->rollBack();
        die("Erro ao cadastrar avaliação: " . $e->getMessage());
    }

} else {
    // Redirecionar se o formulário não foi enviado
    header("Location: ../index.php");
    exit();
}
?>
