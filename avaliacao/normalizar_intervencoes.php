<?php

// Conexão com o banco de dados (exemplo)
require_once ('../database.php'); // Arquivo para conectar ao banco de dados

$banco_dados = new Database;
$conn  = $banco_dados->abrir_conexao();


// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definir a codificação para UTF-8
$conn->set_charset("utf8");

// Consulta SQL para buscar os dados
$sql = "SELECT area_conhecimento, objetivo_especifico, intervencao FROM intervenções ORDER BY area_conhecimento, objetivo_especifico";
$result = $conn->query($sql);

// Array para armazenar os dados normalizados
$dados_normalizados = [];

// Processar o resultado da consulta
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Os dados do banco de dados já estão em UTF-8, então não é necessário utf8_encode
        $area_conhecimento = $row["area_conhecimento"];
        $objetivo_especifico = $row["objetivo_especifico"];
        $intervencao = $row["intervencao"];
        
        // Adicionar ao array de dados normalizados
        if (!isset($dados_normalizados[$area_conhecimento])) {
            $dados_normalizados[$area_conhecimento] = [];
        }
        if (!isset($dados_normalizados[$area_conhecimento][$objetivo_especifico])) {
            $dados_normalizados[$area_conhecimento][$objetivo_especifico] = [];
        }
        
        $dados_normalizados[$area_conhecimento][$objetivo_especifico][] = $intervencao;
    }
}

// Fechar conexão
$conn->close();

// Definir cabeçalho para UTF-8
header('Content-Type: application/json; charset=utf-8');

// Gerar JSON com JSON_UNESCAPED_UNICODE para preservar caracteres Unicode
$json_data = json_encode($dados_normalizados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Especificar o caminho do arquivo onde o JSON será salvo
$caminho_arquivo = 'intervencoes_normalizadas.json';

// Salvar o JSON no arquivo
if (file_put_contents($caminho_arquivo, $json_data) !== false) {
    echo "JSON salvo com sucesso em: " . $caminho_arquivo;
} else {
    echo "Falha ao salvar o JSON em: " . $caminho_arquivo;
}
?>