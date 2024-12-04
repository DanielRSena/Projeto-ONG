<?php
include ( '../verificar_session.php' );
include( '../consulta/consulta_assistido.php' );
verificar( '../view/view.php', 'login.php' );
require_once ( '../view/view.php' );
require_once( '../database.php' );
include( 'arqs/formatarIdade.php' );

$banco_dados = new Database;
$conexao = $banco_dados->abrir_conexao();

if ( $conexao !== false ) {
    $usuario = $_SESSION[ 'user' ];

    // Consulta SQL
    $sql = 'SELECT * FROM usuários WHERE login = ? AND admin = TRUE';

    // Preparando a declaração
    $stmt = $conexao->prepare( $sql );
    $stmt->bind_param( 's', $usuario );

    // Executando a consulta
    $stmt->execute();

    // Obtendo resultados
    $result = $stmt->get_result();

    // Verificando se encontrou algum resultado
    if ( $result->num_rows > 0 ) {
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
View::alert( 'Erro ao conectar com banco de dados' );
?>

<?php require_once ('modelos/header.html');
?>

<h1>Assistidos</h1>

<div class = 'container'>

<?php $assistidos = mostrarAssistidos( $_SESSION[ 'user' ] );

if ( !empty( $assistidos ) ) {
    foreach ( $assistidos as $assistido ) {

        echo "<div class='divDados'>";
        ?>

        <img src = "../mostrarImagem.php?cpf=<?php echo $assistido['cpf']?>&entidade=a" alt = 'Imagem extraída'> &nbsp;
        &nbsp;

        <?php

        echo '<div><div>' . $assistido[ 'cpf' ] . '</div> <br>';

        echo '<div>' . $assistido[ 'nome' ];

        if ( $assistido[ 'sexo' ] == 'Masculino' ) echo "<i class='bi bi-gender-male' style='color: blue;'></i> </div> <br>";
        else echo "<i class='bi bi-gender-female' style='color: red;'></i> </div> <br>";

        echo '<div>' . formatar( $assistido[ 'data_nasc' ] ) . '</div> <br>';

        echo '</div> </div> <br><br>';

    }
} else echo 'Nenhum resultado encontrado.';

?>

</div>

<?php require_once('modelos/footer.html'); ?>