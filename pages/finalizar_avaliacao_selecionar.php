<?php
    include ('../verificar_session.php');
    verificar('../view/view.php', 'login.php');
    require_once ('../view/view.php');
    require_once ('../database.php'); 
    $banco_dados = new Database;
    $conexao = $banco_dados->abrir_conexao();

    function formatarData($data) {
        $data = date_create($data);
        return date_format($data, 'd/m/Y');
    }

    function formatarCPF($cpf) {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
    }
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
    }
    .container {
        width: 60%;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
    }
    .section {
        background-color: #f9f9f9;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
    }
    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 16px;
        color: #666;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    input[type="submit"] {
        display: block;
        width: 50%;
        padding: 10px;
        margin: 20px auto;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #218838;
    }
    .questao-numero {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #ddd;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table, th, td {
        border: 1px solid #ccc;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>

<?php require_once ('modelos/header.html'); ?>

    <div class="container">
        <h1>Finalização da avaliação</h1>
        
        <form method="POST" action="finalizar_avaliacao.php">
            <div class="section">
                <div class="section-title">Testes no aguardo para a avaliação:</div>

                <?php

                if ($conexao->connect_error) {
                    die("Conexão falhou: " . $conexao->connect_error);
                }

                // Busca na tabela avaliacoes_nao_finalizadas
                $sql_nao_finalizadas = "SELECT id_avaliacao FROM avaliacoes_nao_finalizadas";
                $result_nao_finalizadas = $conexao->query($sql_nao_finalizadas);
                $avaliacoes_ids = [];

                if ($result_nao_finalizadas->num_rows > 0) {
                    echo '<table>';
                    echo '<tr><th>ID Avaliação</th><th>Data do Teste</th><th>CPF Assistido</th><th>CPF Psicopedagogo</th></tr>';
                    
                    while($row_nao_finalizadas = $result_nao_finalizadas->fetch_assoc()) {
                        $id_avaliacao = $row_nao_finalizadas['id_avaliacao'];
                        $avaliacoes_ids[] = $id_avaliacao;

                        // Busca na tabela avaliacoes
                        $sql_avaliacoes = "SELECT cpf_assistido, cpf_psico, data_teste FROM avaliações WHERE id = '$id_avaliacao'";
                        $result_avaliacoes = $conexao->query($sql_avaliacoes);

                        if ($result_avaliacoes->num_rows > 0) {
                            while($row_avaliacoes = $result_avaliacoes->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $id_avaliacao . '</td>';
                                echo '<td>' . formatarData($row_avaliacoes['data_teste']) . '</td>';
                                echo '<td>' . formatarCPF($row_avaliacoes['cpf_assistido']) . '</td>';
                                echo '<td>' . formatarCPF($row_avaliacoes['cpf_psico']) . '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                    echo '</table>';

                    // Exibir a caixa seletora com os IDs
                    echo '<div class="form-group">';
                    echo '<label for="id_avaliacao_selecionada">Selecione o ID do teste para avaliar:</label>';
                    echo '<select id="id_avaliacao_selecionada" name="id_avaliacao_selecionada" required>';
                    foreach ($avaliacoes_ids as $id) {
                        echo '<option value="' . $id . '">' . $id . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                } else {
                    echo 'Nenhuma avaliação não finalizada encontrada.';
                }

                $conexao->close();

                ?>
            </div>

            <input type="submit" value="Continuar">
        </form>
    </div>

    

<?php
require_once ('modelos/footer.html');
?>