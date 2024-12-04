<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

require_once('../database.php'); // Arquivo para conectar ao banco de dados

// Função para formatar CPF
function formatar_cpf($cpf) {
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

// Função para formatar CRP
function formatar_crp($crp) {
    return substr($crp, 0, 2) . '/' . substr($crp, 2, 6) . '-IS';
}

// Função para formatar data
function formatar_data($data) {
    // Converte a data do formato AAAA-MM-DD para DD/MM/AAAA
    return date_format(date_create($data), 'd/m/Y');
}

// ID da avaliação a ser consultada (você deve definir isso)
$id_avaliacao = $_GET['id_avaliacao']; // Exemplo: substitua pelo ID da avaliação desejada

$banco_dados = new Database;
$conexao = $banco_dados->abrir_conexao();

// Verificando a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Consulta SQL para obter informações da avaliação
$sql_avaliacao = "SELECT av.id, av.data_teste, av.cpf_assistido, av.cpf_psico, a.nome AS nome_assistido, p.nome AS nome_psicopedagogo, p.num_registro
                  FROM avaliações av
                  INNER JOIN assistidos a ON av.cpf_assistido = a.cpf
                  INNER JOIN psicopedagogos p ON av.cpf_psico = p.cpf
                  WHERE av.id = $id_avaliacao";

$result_avaliacao = $conexao->query($sql_avaliacao);

// Verifica se há resultados
if ($result_avaliacao->num_rows > 0) {
    // Obtém os dados da avaliação
    $row_avaliacao = $result_avaliacao->fetch_assoc();
    $id_avaliacao = $row_avaliacao['id'];
    $data_teste = formatar_data($row_avaliacao['data_teste']);
    $nome_assistido = $row_avaliacao['nome_assistido'];
    $cpf_assistido = formatar_cpf($row_avaliacao['cpf_assistido']);
    $nome_psicopedagogo = $row_avaliacao['nome_psicopedagogo'];
    $cpf_psicopedagogo = formatar_cpf($row_avaliacao['cpf_psico']);
    $num_registro = formatar_crp($row_avaliacao['num_registro']);

    // Início da tabela HTML com estilo
    echo "<table style='border-collapse: collapse; width: 100%; margin-bottom: 20px; border: 1px solid #ddd;'>
            <tr style='background-color: #f2f2f2;'>
                <th style='padding: 10px; border: 1px solid #ddd;'>ID Avaliação/Teste</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Data de aplicação do Teste</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Nome do Assistido</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>CPF do Assistido</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>Nome do Psicopedagogo</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>CPF do Psicopedagogo</th>
                <th style='padding: 10px; border: 1px solid #ddd;'>CRP Psicopedagogo</th>
            </tr>";

    // Exibindo os dados da avaliação
    echo "<tr>
            <td style='padding: 10px; border: 1px solid #ddd;'>$id_avaliacao</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$data_teste</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$nome_assistido</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$cpf_assistido</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$nome_psicopedagogo</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$cpf_psicopedagogo</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>$num_registro</td>
          </tr>";

    // Fim da tabela de informações da avaliação
    echo "</table>";

    // Consulta SQL para obter as questões da avaliação
    $sql_questoes = "SELECT q.id AS id_questao, q.area_conhecimento, q.questao, tq.resposta
                     FROM testes_questoes tq
                     INNER JOIN questões q ON tq.id_questao = q.id
                     WHERE tq.id_avaliacao = $id_avaliacao";

    $result_questoes = $conexao->query($sql_questoes);

    // Verifica se há resultados
    if ($result_questoes->num_rows > 0) {
        // Início da tabela HTML para as questões com estilo
        echo "<table style='border-collapse: collapse; width: 100%; border: 1px solid #ddd;'>
                <tr style='background-color: #f2f2f2;'>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Nº</th>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Área de Conhecimento</th>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Enunciado</th>
                    <th style='padding: 10px; border: 1px solid #ddd;'>Resposta</th>
                </tr>";

        // Definição de cores dinâmicas para as áreas de conhecimento
        $cores = array();
        $cor_index = 0;

        // Loop através dos resultados das questões
        while ($row = $result_questoes->fetch_assoc()) {
            $area_conhecimento = $row['area_conhecimento'];
            if (!isset($cores[$area_conhecimento])) {
                // Gerar cores alternadas entre cinza claro e cinza mais escuro
                $cor = $cor_index % 2 == 0 ? "#f9f9f9" : "#e9e9e9";
                $cores[$area_conhecimento] = $cor;
                $cor_index++;
            }
            $cor = $cores[$area_conhecimento];

            // Número da questão
            $numero_questao = $row['id_questao'];

            // Traduzindo resposta para Sim ou Não
            $resposta_texto = $row['resposta'] == 1 ? "Sim" : "Não";
            $cor_resposta = $row['resposta'] == 1 ? "#8bc34a" : "#f44336";

            // Exibindo os dados das questões na tabela com a cor definida
            echo "<tr style='background-color: $cor;'>
                    <td style='padding: 10px; border: 1px solid #ddd;'>$numero_questao</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>{$row['area_conhecimento']}</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>{$row['questao']}</td>
                    <td style='padding: 10px; border: 1px solid #ddd; background-color: $cor_resposta; color: #fff;'>{$resposta_texto}</td>
                  </tr>";
        }

        // Fim da tabela HTML das questões
        echo "</table>";
    } else {
        echo "Nenhuma questão encontrada para a avaliação com ID $id_avaliacao.";
    }
} else {
    echo "Nenhuma informação encontrada para a avaliação";}
