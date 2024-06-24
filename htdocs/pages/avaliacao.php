<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../view/header.html');


?>


<body>
    <main>
        <h1>Avaliação/Teste</h1>
        <h2 class="subtitle">Aplicar um novo teste ou avaliar um teste já aplicado?</h2>
        <div class="buttonGrande-container">
            <button id="bt1" class="buttonGrande">Aplicar teste</button>
            <button id="bt2" class="buttonGrande">Avaliar teste</button>
        </div>
    </main>

    <script>
        const botao1 = document.getElementById('bt1');
        const botao2 = document.getElementById('bt2');

        botao1.addEventListener('click', function () {
            const url = 'teste.php';

            window.location.href = url;
        });

        botao2.addEventListener('click', function () {
            const url = 'finalizar_avaliacao_selecionar.php';

            window.location.href = url;
        });
    </script>

    <?php
    require_once ('../view/footer.html');
    ?>