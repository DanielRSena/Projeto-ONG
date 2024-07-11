<?php

    require_once ('../database.php'); 

    $banco_dados = new Database;
    $conn  = $banco_dados->abrir_conexao();
    
    if ($conn->connect_error) die("Falha na conexão: " . $conn->connect_error); // Verificar conexão

    $conn->set_charset("utf8"); // Define a codificação para UTF-8

    // Consulta SQL para buscar os dados
    $sql = "SELECT area_conhecimento, objetivo_especifico, intervencao FROM intervenções ORDER BY area_conhecimento, objetivo_especifico";
    $result = $conn->query($sql);

    $dados_normalizados = [];

    // Processar o resultado da consulta
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            
            $area_conhecimento = $row["area_conhecimento"];
            $objetivo_especifico = $row["objetivo_especifico"];
            $intervencao = $row["intervencao"];
            
            // Adicionar ao array de dados normalizados
            if (!isset($dados_normalizados[$area_conhecimento]))
                $dados_normalizados[$area_conhecimento] = [];

            if (!isset($dados_normalizados[$area_conhecimento][$objetivo_especifico])) 
                $dados_normalizados[$area_conhecimento][$objetivo_especifico] = [];
            
            $dados_normalizados[$area_conhecimento][$objetivo_especifico][] = $intervencao;
        }
    }

    $conn->close();

    header('Content-Type: application/json; charset=utf-8'); // Definir cabeçalho para UTF-8

    // Gerar JSON com JSON_UNESCAPED_UNICODE para preservar caracteres Unicode
    $json_data = json_encode($dados_normalizados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $caminho_arquivo = 'intervencoes_normalizadas.json';

    // Salvar o JSON no arquivo
    if (file_put_contents($caminho_arquivo, $json_data) !== false) echo "JSON salvo com sucesso em: " . $caminho_arquivo;
    else echo "Falha ao salvar o JSON em: " . $caminho_arquivo;
?>