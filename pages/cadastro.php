<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../view/header.html');


require_once('../database.php');


$banco_dados = new Database;
$conexao = $banco_dados->abrir_conexao();

if ($conexao !== false) {
    $usuario = $_SESSION['user'];

    // Consulta SQL
    $sql = "SELECT * FROM usuários WHERE login = ? AND admin = TRUE";

    // Preparando a declaração
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $usuario);

    // Executando a consulta
    $stmt->execute();

    // Obtendo resultados
    $result = $stmt->get_result();

    // Verificando se encontrou algum resultado
    if ($result->num_rows > 0) {
        // Usuário possui a coluna admin como TRUE
        $admin = true;
    } else {
        // Usuário não é administrador
        $admin = false;
    }

    // Fechando a declaração e a conexão
    $stmt->close();
    $conexao->close();
} else
    View::alert("Erro ao conectar com banco de dados");
?>


<body>
    <main>
        <h1>Cadastro</h1>
        <h2 class="subtitle">Selecione o item que deseja cadastrar no sistema:</h2>
        <div class="buttonGrande-container">
            <button id="bt1" class="buttonGrande">Assistido</button>
            <button id="bt2" class="buttonGrande">Responsável</button>
            <?php
            if($admin){
                echo "<button id='bt3' class='buttonGrande'>Psicopedagogo</button>";
                echo "<button id='bt4' class='buttonGrande'>Usuário</button>";
            }
            ?>
        </div>
    </main>

    <script>
        const botao1 = document.getElementById('bt1');
        const botao2 = document.getElementById('bt2');
        const botao3 = document.getElementById('bt3');
        const botao4 = document.getElementById('bt4');

        botao1.addEventListener('click', function () {
            const url = 'cadastro_assistido.php';

            window.location.href = url;
        });

        botao2.addEventListener('click', function () {
            const url = 'cadastro_responsavel.php';

            window.location.href = url;
        });

        botao3.addEventListener('click', function () {
            const url = 'cadastro_psicopedagogo.php';

            window.location.href = url;
        });

        botao4.addEventListener('click', function () {
            const url = 'cadastro_usuario.php';

            window.location.href = url;
        });
    </script>

    <?php
    require_once ('../view/footer.html');
    ?>