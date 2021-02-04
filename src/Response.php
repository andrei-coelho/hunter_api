<?php 

namespace src;

class Response {
 
    public static function error(int $num = 404){
        echo "Erro ".$num."<br>";
        exit;
    }
    
}