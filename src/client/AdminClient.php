<?php 

namespace src\client;

use src\sqli\SQLi as sqli;

class AdminClient implements Client {

    public function __construct($slugClient, $chave = "") {
        
        $res = sqli::query("SELECT admin.id
            FROM admin 
            WHERE admin.chave = '$chave'
        ");

        if(!$res || $res->rowCount() == 0){
            throw new Exception("", 1);
        }
        
    }

}