<?php
include ('../verificar_session.php');
verificar('../view/view.php', 'login.php');
require_once ('../view/view.php');
require_once ('../database.php'); // Arquivo para conectar ao banco de dados

$banco_dados = new Database;
$conexao = $banco_dados->abrir_conexao();

$id_avaliacao = $_POST['id_avaliacao_selecionada'];

// Feche a conexão após a obtenção do ID de avaliação
$conexao->close();
?>



<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
        height: 100vh;
    }

    .containerAvaliacao {
        width: 60%;
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
        position: relative;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        font-size: 16px;
        color: #666;
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
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

    .questao-numero {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #ddd;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ccc;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .btn-visualizar {
        display: block;
        width: 100%;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s;
    }

    .btn-visualizar:hover {
        background-color: #0056b3;
    }
</style>


<?php require_once('modelos/header.html') ?>

    <div class="containerAvaliacao">
        <h1>Finalização da avaliação</h1>

        <div class="section">
            <div class="section-title">Visualizar o teste escolhido:</div>

            <!-- Exibir informações do teste selecionado -->
            <?php
            echo '<p>ID do Teste Selecionado: ' . htmlspecialchars($id_avaliacao) . '</p>';
            ?>

            <!-- Botão para visualizar o teste -->
            <a href="../avaliacao/visualizar_teste.php?id_avaliacao=<?php echo $id_avaliacao; ?>" class="btn-visualizar"
                target="_blank">
                Visualizar Teste
            </a>
        </div>

        <div class="section">
            <div class="section-title">Adicionar intervenções:</div>

            <form id="formAdicionar">
                <div class="form-group">
                    <label for="area_conhecimento">Área de Conhecimento:</label>
                    <select id="area_conhecimento" name="area_conhecimento" onchange="carregarObjetivos()">
                        <!-- Options serão preenchidos dinamicamente com JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="objetivo_especifico">Objetivo Específico:</label>
                    <select id="objetivo_especifico" name="objetivo_especifico" onchange="carregarIntervencoes()">
                        <!-- Options serão preenchidos dinamicamente com JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="intervencao">Intervenção:</label>
                    <select id="intervencao" name="intervencao">
                        <!-- Options serão preenchidos dinamicamente com JavaScript -->
                    </select>
                </div>

                <input type="button" value="Adicionar" onclick="adicionarLinha()">
            </form>
        </div>

        <div class="section">
            <div class="section-title">Intervenções adicionadas:</div>

            <table id="tabelaInformacoes">
                <thead>
                    <tr>
                        <th>Área de Conhecimento</th>
                        <th>Objetivo Específico</th>
                        <th>Intervenção</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Linhas serão adicionadas dinamicamente com JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Adicionar Resultado e Diagnóstico:</div>

            <form id="formResultadoDiagnostico" action="../avaliacao/processar_avaliacao_completa.php" method="post">
                <!-- Campos para resultado e diagnóstico -->
                <div class="form-group">
                    <label for="resultado">Resultado:</label>
                    <textarea id="resultado" name="resultado" placeholder="Digite o resultado aqui"
                        rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="diagnostico">Diagnóstico:</label>
                    <textarea id="diagnostico" name="diagnostico" placeholder="Digite o diagnóstico aqui"
                        rows="6"></textarea>
                </div>

                <!-- Campos ocultos para enviar dados da tabela de intervenções -->
                <input type="hidden" id="tabelaIntervencoes" name="tabelaIntervencoes">

                <input type="hidden" name="id_avaliacao_selecionada" value="<?php echo $id_avaliacao; ?>">

                <input type="submit" value="Finalizar avaliação">
            </form>
        </div>

    </div>

    <script>
        let dados;

        async function carregarDados() {
            try {
                const response = await fetch('../avaliacao/intervencoes_normalizadas.json');
                dados = await response.json();
                carregarAreasConhecimento();
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            }
        }

        function carregarAreasConhecimento() {
            const areaConhecimentoSelect = document.getElementById('area_conhecimento');
            areaConhecimentoSelect.innerHTML = '<option value="">Selecione</option>';
            for (const area in dados) {
                const option = document.createElement('option');
                option.value = area;
                option.textContent = area;
                areaConhecimentoSelect.appendChild(option);
            }
        }

        function carregarObjetivos() {
            const areaConhecimento = document.getElementById('area_conhecimento').value;
            const objetivoSelect = document.getElementById('objetivo_especifico');
            objetivoSelect.innerHTML = '<option value="">Selecione</option>';
            if (dados[areaConhecimento]) {
                for (const objetivo in dados[areaConhecimento]) {
                    const option = document.createElement('option');
                    option.value = objetivo;
                    option.textContent = objetivo;
                    objetivoSelect.appendChild(option);
                }
            }
        }

        function carregarIntervencoes() {
            const areaConhecimento = document.getElementById('area_conhecimento').value;
            const objetivo = document.getElementById('objetivo_especifico').value;
            const intervencaoSelect = document.getElementById('intervencao');
            intervencaoSelect.innerHTML = '<option value="">Selecione</option>';
            if (dados[areaConhecimento] && dados[areaConhecimento][objetivo]) {
                for (const intervencao of dados[areaConhecimento][objetivo]) {
                    const option = document.createElement('option');
                    option.value = intervencao;
                    option.textContent = intervencao;
                    intervencaoSelect.appendChild(option);
                }
            }
        }

        function adicionarLinha() {
            var areaConhecimento = document.getElementById('area_conhecimento').value;
            var objetivoEspecifico = document.getElementById('objetivo_especifico').value;
            var intervencao = document.getElementById('intervencao').value;

            var tabela = document.getElementById('tabelaInformacoes').getElementsByTagName('tbody')[0];
            var novaLinha = tabela.insertRow();

            var colunaArea = novaLinha.insertCell(0);
            colunaArea.textContent = areaConhecimento;

            var colunaObjetivo = novaLinha.insertCell(1);
            colunaObjetivo.textContent = objetivoEspecifico;

            var colunaIntervencao = novaLinha.insertCell(2);
            colunaIntervencao.textContent = intervencao;

            var colunaAcoes = novaLinha.insertCell(3);
            var botaoExcluir = document.createElement('button');
            botaoExcluir.textContent = 'Excluir';
            botaoExcluir.onclick = function() {
                tabela.deleteRow(novaLinha.rowIndex);
            };
            colunaAcoes.appendChild(botaoExcluir);

            // Atualiza o campo oculto com os dados da tabela de intervenções
            atualizarCampoOculto();
        }

        function atualizarCampoOculto() {
            var tabela = document.getElementById('tabelaInformacoes');
            var linhas = tabela.rows;
            var dadosIntervencoes = [];

            // Começa do index 1 para pular o cabeçalho
            for (var i = 1; i < linhas.length; i++) {
                var linha = linhas[i];
                var areaConhecimento = linha.cells[0].textContent;
                var objetivoEspecifico = linha.cells[1].textContent;
                var intervencao = linha.cells[2].textContent;

                dadosIntervencoes.push({
                    area_conhecimento: areaConhecimento,
                    objetivo_especifico: objetivoEspecifico,
                    intervencao: intervencao
                });
            }

            // Atualiza o campo oculto com os dados em JSON
            document.getElementById('tabelaIntervencoes').value = JSON.stringify(dadosIntervencoes);
        }

        document.addEventListener('DOMContentLoaded', carregarDados);
    </script>
</body>

<?php
require_once ('modelos/footer.html');
?>
