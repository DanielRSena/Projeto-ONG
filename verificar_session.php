<?php

function verificar($endereco_view, $endereco_login)
{
    require_once($endereco_view);

    session_start();

    if (!isset($_SESSION['logado']) || $_SESSION["logado"] != TRUE) {
        View::abrir($endereco_login);
    }

}