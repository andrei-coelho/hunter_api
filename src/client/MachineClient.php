<?php 

namespace src\client;

use src\sqli\SQLi as sqli;

class MachineClient implements Client {

    public function __construct($slugClient, $chave = "") {
        
        $res = sqli::query("SELECT machine.id
            FROM machine 
            JOIN cliente ON machine.id = cliente.machine
            WHERE machine.chave = '$chave' 
            AND cliente.slug = '$slugClient' 
        ");
    
        if(!$res || $res->rowCount() == 0){
            throw new \Exception("", 1);
        }

    }

}