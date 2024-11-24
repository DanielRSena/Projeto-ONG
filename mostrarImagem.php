<?php

    include('verificar_session.php');
    include('database.php');

    $database = new Database();
    $conexao = $database->abrir_conexao();

    $cpf = $_GET['cpf'];

    $sql = "SELECT foto FROM assistidos WHERE cpf = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $imagem_blob = $row['foto'];

        if (!empty($imagem_blob)) {
            header('Content-Type: image/jpeg'); 
            echo $imagem_blob;
        } 
        else {
            
            $semImagem = 'pages/img/semFoto.png';  // Caminho da imagem padrão
            if (file_exists($semImagem)) {
                header('Content-Type: image/png');
                readfile($semImagem);
            } 
            else echo "Imagem padrão não encontrada!";
        }
    } 
    else echo "Nenhuma imagem encontrada para o CPF fornecido!";
    
    $conexao->close();
?>