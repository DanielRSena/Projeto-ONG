<?php

require_once ('view/view.php');

class Database
{
    // Método para abrir conexão usando MySQLi
    public function abrir_conexao()
    {
        $conexao = mysqli_connect("localhost", "root", "", "gemtes");

        if(!$conexao){
            View::alert("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
            return false;
        } else {
            return $conexao;
        }
    }

    // Método para fechar a conexão MySQLi
    public function fechar_conexao($conexao){
        mysqli_close($conexao);
    }

    // Método para gerar uma conexão PDO
    public function gerar_PDO(){
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gemtes", 'root', '');
            // Configuração adicional do PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Outras configurações opcionais
            return $pdo;
        } catch (PDOException $e) {
            View::alert("Erro ao conectar com o banco de dados usando PDO: " . $e->getMessage());
            return false;
        }
    }
}