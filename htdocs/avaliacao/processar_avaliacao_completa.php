<?php
require_once('../database.php'); // Arquivo para conectar ao banco de dados

// Verifica se o formulário foi submetido corretamente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $resultado = $_POST['resultado'];
    $diagnostico = $_POST['diagnostico'];
    $intervencoes = json_decode($_POST['tabelaIntervencoes'], true);
    $id_avaliacao = $_POST['id_avaliacao_selecionada'];

    // Conectar ao banco de dados
    $banco_dados = new Database;
    $conexao = $banco_dados->abrir_conexao();

    // Atualiza a tabela de avaliações com resultado e diagnóstico
    $query_update = "UPDATE avaliações SET resultado = ?, diagnostico = ?, data_fim_avaliacao = CURRENT_TIMESTAMP WHERE id = ?";
$stmt_update = $conexao->prepare($query_update);
$stmt_update->bind_param("ssi", $resultado, $diagnostico, $id_avaliacao);
$stmt_update->execute();

    // Verifica se houve sucesso na atualização
    if ($stmt_update->affected_rows > 0) {
        // Insere as intervenções na tabela avaliacoes_intervencoes
        foreach ($intervencoes as $intervencao) {
            $area_conhecimento = $intervencao['area_conhecimento'];
            $objetivo_especifico = $intervencao['objetivo_especifico'];
            $intervencao_texto = $intervencao['intervencao'];

            $query_insert = "INSERT INTO avaliacoes_intervencoes (id_avaliacao, intervencao) VALUES (?, ?)";
            $stmt_insert = $conexao->prepare($query_insert);
            $stmt_insert->bind_param("is", $id_avaliacao, $intervencao_texto);
            $stmt_insert->execute();
        }

        // Verifica se todas as inserções foram bem-sucedidas
        if ($conexao->affected_rows > 0) {
            echo "Avaliação finalizada com sucesso.";
            sleep(5);
            View::abrir('../home.php');
        } else {
            echo "Erro ao inserir intervenções na avaliação.";
        }
    } else {
        echo "Erro ao atualizar resultado e diagnóstico da avaliação.";
    }

    // Fecha a conexão com o banco de dados
    $conexao->close();
} else {
    // Se o método de requisição não for POST, redireciona ou exibe mensagem de erro adequada
    echo "Erro: Método de requisição inválido.";
}
?>
