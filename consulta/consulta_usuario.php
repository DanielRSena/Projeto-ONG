<?php 

function mostrarUsuarios() {

    return fazerConsulta("SELECT * FROM usuários");
}
function fazerConsulta(string $sql) {
    $conexao = mysqli_connect("localhost", "root", "", "gemtes") or die("Falha na conexão");
    $consulta = mysqli_query($conexao, $sql) or die("Falha na consulta");
    $registros = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
    return $registros;
}
?>