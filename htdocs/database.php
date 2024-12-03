<?php

require_once ('view/view.php');

class Database
{
    public function abrir_conexao()
    {
        $conexao = mysqli_connect("localhost", "root", "0000", "gemtes") or die(false);

        if(!$conexao){
            View::alert("Erro ao conectar com o banco de dados");
            return false;
        } else{
            return $conexao;
        }
    }

    public function fechar_conexao($conexao){
        mysqli_close($conexao);
    }

    public function gerar_PDO(){
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gemtes", 'root', '0000');
            // Configuração adicional do PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Outras configurações opcionais
            return $pdo;
        } catch (PDOException $e) {
            return false;
        }
    }
}