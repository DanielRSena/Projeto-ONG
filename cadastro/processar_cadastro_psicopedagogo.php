<?php
// Incluir arquivo de configuração do banco de dados
require_once('../database.php'); // Substitua pelo seu arquivo de conexão
require_once('../view/view.php');

$banco_dados = new Database;
$pdo = $banco_dados->gerar_PDO();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar os dados do formulário
    $nome = $_POST['nome-psicopedagogo'];
    $cpf = $_POST['cpf-psicopedagogo'];
    $crp = $_POST['crp-psicopedagogo'];

    // Limpar o CPF removendo caracteres especiais (deixando apenas números)
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $crp = preg_replace("/[^0-9]/", "", $crp);

    // Manipular o upload da foto
    $fotoNome = $_FILES['foto-psicopedagogo']['name'];
    $fotoTmp = $_FILES['foto-psicopedagogo']['tmp_name'];

    // Verificar se um arquivo foi selecionado
    if (!empty($fotoNome)) {
        // Diretório onde a imagem será armazenada
        $diretorio = '../uploads/'; // ajuste o diretório conforme sua estrutura

        // Gerar um nome único para a imagem
        $fotoNomeFinal = $diretorio . uniqid('img_') . '_' . $cpf;

        // Mover a imagem do diretório temporário para o definitivo
        if (move_uploaded_file($fotoTmp, $fotoNomeFinal)) {
            // Preparar a imagem para ser salva no banco de dados (transformando em blob)
            $fotoConteudo = file_get_contents($fotoNomeFinal); // lê o arquivo como blob

            // Preparar a consulta SQL para inserção
            $sql = "INSERT INTO psicopedagogos (nome, cpf, num_registro, foto) 
                    VALUES (:nome, :cpf, :crp, :foto)";

            // Preparar e executar a declaração usando PDO
            $stmt = $pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':crp', $crp);
            $stmt->bindParam(':foto', $fotoConteudo, PDO::PARAM_LOB); // indica que é um parâmetro blob

            // Executar a inserção
            try {
                $stmt->execute();
                View::alert("Dados inseridos com sucesso!");
            } catch (PDOException $e) {
                View::alert("Erro ao inserir dados. Verifique se preencheu todos os campos e se as dependências estão cadastradas. " . $e->getMessage());
            }

            // Apagar o arquivo temporário
            unlink($fotoNomeFinal);
        } else {
            die("Erro ao mover o arquivo para o diretório permanente.");
        }
    } else {
        die("Por favor, selecione uma imagem para upload.");
    }
} else {
    // Redirecionar se o formulário não foi enviado
    header("Location: ../index.php");
    exit();
}
?>
