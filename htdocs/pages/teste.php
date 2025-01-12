<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../view/header.html');
?>

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333; /* Cor preta para o texto dos enunciados */
        }

        .container {
            width: 60%; /* Aumentei um pouco para melhorar a visualização */
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .section {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative; /* Para posicionamento relativo do número da questão */
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px; /* Tamanho menor para a área de conhecimento */
            color: #666; /* Cinza claro para o texto */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .radio-group {
            display: flex;
            justify-content: flex-start;
            margin-top: 8px;
        }

        .radio-group label {
            flex-basis: 18%;
            text-align: center;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            transition: background-color 0.3s;
        }

        .radio-group label:hover {
            background-color: #f9f9f9;
        }

        .radio-group input[type="radio"] {
            display: none;
        }

        .radio-group input[type="radio"]:checked+label {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        input[type="submit"] {
            display: block;
            width: 50%;
            padding: 10px;
            margin: 20px auto;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Estilos para o número da questão */
        .questao-numero {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #ddd; /* Fundo cinza claro para o número da questão */
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>

<body>
<body>
    <div class="container">
        <h1>Aplicação de Teste</h1>
        
        <form method="POST" action="../avaliacao/processar_teste.php">
            <div class="section">
                <div class="section-title">Insira os dados para o registro do teste:</div>
                <div class="form-group">
                    <label for="data_aplicacao">Data início da aplicação da avaliação (data do teste):</label>
                    <input type="date" id="data_aplicacao" name="data_aplicacao" required>
                </div>

                <div class="form-group">
                    <label for="cpf_assistido">CPF do assistido:</label>
                    <input type="text" id="cpf_assistido" name="cpf_assistido" required>
                </div>

                <div class="form-group">
                    <label for="cpf_psicopedagogo">CPF do psicopedagogo:</label>
                    <input type="text" id="cpf_psicopedagogo" name="cpf_psicopedagogo" required>
                </div>
            </div>


            <hr>

            <?php
            // Conexão com o banco de dados
            require_once('../database.php');
            $banco_dados = new Database;
            $conexao = $banco_dados->abrir_conexao();

            if ($conexao->connect_error) {
                die("Conexão falhou: " . $conexao->connect_error);
            }

            // Consulta SQL para buscar as questões por área de conhecimento
            $sql = "SELECT id, area_conhecimento, questao FROM questões";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                $numero_questao = 1;
                while ($row = $result->fetch_assoc()) {
                    // Exibição de cada questão como um grupo de radio buttons
                    echo '<div class="section">';
                    echo '<div class="section-title">' . htmlspecialchars($row['area_conhecimento']) . '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="questao_' . $row['id'] . '">' . htmlspecialchars($row['questao']) . '</label>';
                    echo '<div class="radio-group">';
                    echo '<input type="radio" id="resposta_' . $row['id'] . '_1" name="resposta_' . $row['id'] . '" value="0" required>';
                    echo '<label for="resposta_' . $row['id'] . '_1">Sim</label>';
                    echo '<input type="radio" id="resposta_' . $row['id'] . '_2" name="resposta_' . $row['id'] . '" value="1">';
                    echo '<label for="resposta_' . $row['id'] . '_2">Não</label>';
                    echo '</div>';
                    echo '<div class="questao-numero">Questão ' . $numero_questao . '</div>'; // Número da questão
                    echo '</div>';
                    echo '</div>';
                    $numero_questao++;
                }
            } else {
                echo "Não foram encontradas questões.";
            }

            $conexao->close();
            ?>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
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

        document.getElementById(campo).value = cpf;

        return cpf;
    }

    document.getElementById('cpf_assistido').addEventListener('input', function (event) {
        var cpf = event.target.value;
        formatarCPF(cpf, 'cpf_assistido');
    });

    document.getElementById('cpf_psicopedagogo').addEventListener('input', function (event) {
        var cpf = event.target.value;
        formatarCPF(cpf, 'cpf_psicopedagogo');
    });
    </script>

<?php
require_once ('../view/footer.html');
?>
