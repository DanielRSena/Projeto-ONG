<?php

    require_once ('view/view.php');

    class Database {

        public function abrir_conexao() {

            $conexao = mysqli_connect("localhost", "root", "", "gemtes");

            if(!$conexao) {
                View::alert("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
                return false;
            } else return $conexao;
        }

        public function fechar_conexao($conexao){
            mysqli_close($conexao);
        }

        public function gerar_PDO(){
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=gemtes", 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                return $pdo;
            } catch (PDOException $e) {
                View::alert("Erro ao conectar com o banco de dados usando PDO: " . $e->getMessage());
                return false;
            }
        }
    }
?>