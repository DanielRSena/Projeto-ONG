<?php
    function formatar($nasc){

        $datetime = new DateTime($nasc);

        $ano = $datetime->format('Y');
        $mes = $datetime->format('m');

        $idade = date('Y') - $ano;

        $nasc = date('d/m/Y');

        $resultado = $nasc . ", " . $idade . " anos";

        return $resultado;
    }
?>