<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../view/header.html');
?>

<style>
    #nivel-escolaridade {
        width: 100%;
        /* Faz a caixa de seleção ocupar 100% da largura disponível */
        padding: 6px;
        /* Adiciona preenchimento interno para melhorar a aparência */
        font-size: 16px;
        /* Tamanho da fonte */
        border: 1px solid #ccc;
        /* Borda fina cinza */
        border-radius: 3px;
        /* Borda arredondada */
        box-sizing: border-box;
        /* Garante que o preenchimento e a borda não aumentem o tamanho total */
    }
</style>

<main>
    <div class="dados" id='container'>
        <h2>Cadastro de responsável</h2>
        <form action="../cadastro/processar_cadastro_responsavel.php" method="POST">
            <label for="nome-responsavel">Nome:</label>
            <input type="text" id="nome-responsavel" name="nome-responsavel">
            <label for="cpf-responsavel">CPF:</label>
            <input type="text" id="cpf-responsavel" name="cpf-responsavel">
            <label for="email-responsavel">E-mail:</label>
            <input type="text" id="email-responsavel" name="email-responsavel">

            <!-- Dropdown para nível de escolaridade -->
            <label for="nivel-escolaridade">Nível de escolaridade:</label>
            <select id="nivel-escolaridade" name="nivel-escolaridade">
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
</main>


<script>
    function formatarCPF(cpf, campo) {
        // Remove caracteres não numéricos
        cpf = cpf.replace(/\D/g, '');

        // Limita o tamanho máximo do CPF para 11 dígitos
        if (cpf.length > 11) {
            cpf = cpf.slice(0, 11);
        }

        // Aplica a máscara
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        // Define o valor formatado no campo correto
        if (campo === 'cpf-responsavel') {
            document.getElementById('cpf-responsavel').value = cpf;
        }

        return cpf;
    }

    document.getElementById('cpf-responsavel').addEventListener('input', function (event) {
        var cpf = event.target.value;
        formatarCPF(cpf, 'cpf-responsavel');
    });
</script>

<?php
require_once ('../view/footer.html');
?>