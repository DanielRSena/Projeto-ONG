<?php
    include('../verificar_session.php');
    verificar('../view/view.php', 'login.php');
    require_once('../view/view.php');
?>

<?php require_once('modelos/header.html'); ?>

<div class="dados">

    <h2>Cadastro de assistido</h2>

    <form action="../cadastro/processar_cadastro_assistido.php" method="POST" enctype="multipart/form-data">

        <label for="nome-paciente">Nome:</label>
        <input type="text" id="nome-paciente" name="nome-paciente" required>

        <label for="data-nascimento-paciente">Data de Nascimento:</label>
        <input type="date" id="data-nascimento-paciente" name="data-nascimento-paciente" required>

        <label for="cpf-paciente">CPF:</label>
        <input type="text" id="cpf-paciente" name="cpf-paciente" required>

        <div class="gender-container">
            <label>Gênero:</label>
            <input type="radio" id="genero-masculino" name="genero" value="masculino" required>
            <label for="genero-masculino">Masculino</label>
            <input type="radio" id="genero-feminino" name="genero" value="feminino" required>
            <label for="genero-feminino">Feminino</label>
        </div>

        <label for="observacoes-paciente">Observações:</label>
        <textarea id="observacoes-paciente" name="observacoes-paciente" rows="4"></textarea>

        <label for="foto-paciente">Foto:</label>
        <input type="file" id="foto-paciente" name="foto-paciente" accept="image/*" required>

        <hr>

        <label for="cpfResponsavel-paciente">CPF do responsável:</label>
        <input type="text" id="cpfResponsavel-paciente" name="cpfResponsavel-paciente" required>
        <div class="button-container">
            <button type="submit" class="button">Inserir novo assistido</button>
        </div>

    </form>
</div>
   

    <script>

        // Adiciona evento para formatar enquanto digita - CPF do paciente
        document.getElementById('cpf-paciente').addEventListener('input', function(event) {
            var cpf = event.target.value;
            formatarCPF(cpf, 'cpf-paciente');
        });

        // Adiciona evento para formatar enquanto digita - CPF do responsável
        document.getElementById('cpfResponsavel-paciente').addEventListener('input', function(event) {
            var cpf = event.target.value;
            formatarCPF(cpf, 'cpfResponsavel-paciente');
        });
    </script>

    <?php
    require_once('modelos/footer.html');
    ?>
</body>

</html>