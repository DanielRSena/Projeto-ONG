<?php

require_once('view\view.php');
include('database.php');

class Session
{
    public static function verificaLogin()
    {
        session_start();
        return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
    }

    public static function signIn($username, $password)
    {
                $banco_dados = new Database;
        $conexao = $banco_dados->abrir_conexao();

        if ($conexao !== false) {
            // Usando prepared statement para evitar SQL Injection
            $query = "SELECT senha_hash FROM usuários WHERE login = ?";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $senha_hash);
                mysqli_stmt_fetch($stmt);

                // Verifica se a senha fornecida corresponde à senha hash
                if (password_verify($password, $senha_hash)) {
                    session_cache_expire(30);
                    session_start();
                    $_SESSION["logado"] = TRUE;
                    $_SESSION["user"] = $username;
                    echo 'true';
                } else {
                    echo "Usuário ou senha incorretos";
                }
            } else {
                echo "Usuário não encontrado";
            }

            mysqli_stmt_close($stmt);
        } else {
            View::alert("Erro ao conectar ao banco de dados");
        }

        $banco_dados->fechar_conexao($conexao);
    }


    public static function signOut()
    {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: pages/login.php");
        exit();
    }
}

// Verificar se houve submissão via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e processa os dados recebidos via POST
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    Session::signIn($username, $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    Session::signOut();
}
?>
