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

    
    if(!$admin){
        View::abrir("../home.php");
    }

} else
    View::alert("Erro ao conectar com banco de dados");



?>

<main>
    <div class="dados">
        <h2>Cadastro de psicopedagogo</h2>
        <form action="../cadastro/processar_cadastro_psicopedagogo.php" method="POST" enctype="multipart/form-data">
            <label for="nome-psicopedagogo">Nome:</label>
            <input type="text" id="nome-psicopedagogo" name="nome-psicopedagogo" required>
            
            <label for="cpf-psicopedagogo">CPF:</label>
            <input type="text" id="cpf-psicopedagogo" name="cpf-psicopedagogo" required>
            
            <label for="crp-psicopedagogo">Número de Registro (CRP):</label>
            <input type="text" id="crp-psicopedagogo" name="crp-psicopedagogo" required>

            <label for="foto-psicopedagogo">Foto:</label>
            <input type="file" id="foto-psicopedagogo" name="foto-psicopedagogo">
            
            <div class="button-container">
                <button type="submit" id="bt1" class="button">Inserir novo psicopedagogo</button>
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
        if (campo === 'cpf-psicopedagogo') {
            document.getElementById('cpf-psicopedagogo').value = cpf;
        }

        return cpf;
    }

    document.getElementById('cpf-psicopedagogo').addEventListener('input', function (event) {
        var cpf = event.target.value;
        formatarCPF(cpf, 'cpf-psicopedagogo');
    });


    function formatarCRP(crp, campo) {
    // Remove caracteres não numéricos
    crp = crp.replace(/\D/g, '');

    // Limita o tamanho máximo do CRP para 8 dígitos (considerando o número de registro)
    if (crp.length > 8) {
        crp = crp.slice(0, 8);
    }

    // Aplica a máscara
    crp = crp.replace(/^(\d{2})(\d{6})$/, '$1/$2-IS');

    // Define o valor formatado no campo correto
    if (campo === 'crp-psicopedagogo') {
        document.getElementById('crp-psicopedagogo').value = crp;
    }

    return crp;
}

document.getElementById('crp-psicopedagogo').addEventListener('input', function (event) {
    var crp = event.target.value;
    formatarCRP(crp, 'crp-psicopedagogo');
});


</script>

<?php
require_once ('../view/footer.html');
?>