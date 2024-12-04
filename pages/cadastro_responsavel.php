<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
?>

<?php require_once ('modelos/header.html'); ?>

<div class="dados" id='container'>
    <h2>Cadastro de responsável</h2>
    <form action="../cadastro/processar_cadastro_responsavel.php" method="POST">
        <label for="nome-responsavel">Nome:</label>
        <input type="text" id="nome-responsavel" name="nome-responsavel" required>
        <label for="cpf-responsavel">CPF:</label>
        <input type="text" id="cpf-responsavel" name="cpf-responsavel" required>
        <label for="email-responsavel">E-mail:</label>
        <input type="text" id="email-responsavel" name="email-responsavel" required>

        <!-- Dropdown para nível de escolaridade -->
        <label for="nivel-escolaridade">Nível de escolaridade:</label>
        <select id="nivel-escolaridade" name="nivel-escolaridade" required>
            <option value="Sem escolaridade">Sem escolaridade</option>
            <option value="Educação Infantil Incompleto">Educação Infantil Incompleto</option>
            <option value="Educação Infantil Completo">Educação Infantil Completo</option>
            <option value="Ensino Fundamental Incompleto">Ensino Fundamental Incompleto</option>
            <option value="Ensino Fundamental Completo">Ensino Fundamental Completo</option>
            <option value="Ensino Médio Incompleto">Ensino Médio Incompleto</option>
            <option value="Ensino Médio Completo">Ensino Médio Completo</option>
            <option value="Ensino Técnico Incompleto">Ensino Técnico Incompleto</option>
            <option value="Ensino Técnico Completo">Ensino Técnico Completo</option>
            <option value="Ensino Superior Incompleto">Ensino Superior Incompleto</option>
            <option value="Ensino Superior Completo">Ensino Superior Completo</option>
        </select>

        <!-- Adicione os demais campos necessários para o registro do responsável -->

        <div class="button-container">
            <button type="submit" class="button">Inserir novo responsável</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('cpf-responsavel').addEventListener('input', function (event) {
        var cpf = event.target.value;
        formatarCPF(cpf, 'cpf-responsavel');
    });
</script>

<?php
require_once ('modelos/footer.html');
?>