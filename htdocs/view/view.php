<?php


class View{

    public static function abrir($local)
    {
        header("Location: " . $local);
    }
    
    public static function alert($mensagem)
    {
        echo $mensagem;
    }

    public static function exibir($file){
        $html = file_get_contents($file);
        return $html;
    }

}
