<?php
    if (isset($_GET['cpf'])) {
        $con = mysqli_connect("localhost", "root", "", "gemtes") or die("Falha na conexão");
        $cpf = $_GET['cpf'];
        $query = "DELETE FROM assistidos WHERE cpf = '$cpf'";
        mysqli_query($con, $query);
        header("Location: ../pages/consulta_assistido.php");
    } 
?>