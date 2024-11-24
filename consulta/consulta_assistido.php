<?php 

function mostrarAssistidos() {
    
    $sql = "";

    if(isset($_SESSION['login']) && $_SESSION['login'] != "root") {
        $conexao = mysqli_connect("localhost", "root", "", "gemtes") or die("Falha na conexão");
    
        // Pega o cpf do psicopedagogo
        
        $sql = "SELECT a.* FROM assistidos a JOIN avaliações av ON a.cpf = av.cpf_assistido WHERE av.cpf_psico = ?";

    }

    else $sql = "SELECT * FROM assistidos";

    return fazerConsulta($sql);

}

function fazerConsulta(string $sql) {
    $conexao = mysqli_connect("localhost", "root", "", "gemtes") or die("Falha na conexão");
    $consulta = mysqli_query($conexao, $sql) or die("Falha na consulta");
    $registros = mysqli_fetch_all($consulta, MYSQLI_ASSOC);
    return $registros;
}
?>