<?php 

namespace src\client;

use src\sqli\SQLi as sqli;

class MachineClient extends DataClient implements Client {

    public function __construct($chave, $data = []) {
        if(($slugClient = isset($data['HUNTER-SLUG-CLIENT']) ? $data['HUNTER-SLUG-CLIENT'] : false)){
            $res = sqli::query("SELECT 
            machine.id as machine_id,
            machine.chave as machine_chave,
            machine.slug as machine_slug,
            cliente.*
                FROM machine 
                JOIN cliente ON machine.id = cliente.machine
                WHERE machine.chave = '$chave' 
                AND cliente.slug = '$slugClient' 
            ");
        } else {
            $res = sqli::query("SELECT 
                machine.chave as machine_chave,
                machine.id as machine_id
                FROM machine 
                WHERE machine.chave = '$chave'
            ");
        }
    
        if(!$res || $res->rowCount() != 1){
            throw new \Exception("", 1);
        }

        $this->dataClient = $res->fetchAssoc();

    }

}