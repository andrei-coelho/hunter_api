<?php 

namespace src\client;

use src\sqli\SQLi as sqli;

class UserClient implements Client {

    public function __construct($slugClient, $chave = ""){

        $res = sqli::query("SELECT usuario.id
            FROM usuario 
            JOIN cliente ON cliente.id = usuario.id_cliente
            WHERE usuario.chave = '$chave' 
            AND cliente.slug = '$slugClient' 
        ");

        if(!$res || $res->rowCount() == 0){
            throw new \Exception("", 1);
        }
        
    }

}