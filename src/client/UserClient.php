<?php 

namespace src\client;

use src\sqli\SQLi as sqli;

class UserClient implements Client {

    public function __construct($slugClient, $chave = ""){

        // fazer um join e validar com a slug do cliente
        $res = sqli::query("SELECT * FROM usuario WHERE chave = '$chave'");
        if($res->rowCount() == 0){
            throw new Exception("", 1);
        }
        
    }

}