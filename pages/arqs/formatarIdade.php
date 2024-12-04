<?php


    function formatar($nasc){

        $datetime = new DateTime($nasc);

        $ano = $datetime->format('Y');
        $mes = $datetime->format('m');

        $idade = date('Y') - $ano;

        $nasc = date('d/m/Y');

        $resultado = $nasc . ", " . $idade . " anos";

        return $resultado;
    }
    function gerarImagem($entidade, $cpf) {
        $database = new Database();
        $conexao = $database->abrir_conexao();
    
        $sql = "SELECT foto FROM assistidos WHERE cpf = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imagem_blob = $row['foto'];
    
            if (!empty($imagem_blob)) {
                // Envia a imagem do banco de dados diretamente
                header('Content-Type: image/jpeg'); 
                echo $imagem_blob; // Aqui é necessário usar echo para enviar o conteúdo binário
                exit; // Terminando o script após a imagem ser enviada
            } else {
                $semImagem = '../pages/img/semFoto.png';  // Caminho da imagem padrão
                if (file_exists($semImagem)) {
                    // Envia a imagem padrão
                    header('Content-Type: image/png');
                    readfile($semImagem);
                    exit; // Terminando o script após a imagem padrão ser enviada
                } else {
                    echo "Imagem padrão não encontrada!";
                }
            }
        } else {
            echo "Nenhuma imagem encontrada para o CPF fornecido!";
        }
    
        $conexao->close();
    }
    
?>