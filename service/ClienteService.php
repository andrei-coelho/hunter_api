<?php 

namespace service;

use src\Response as Response;
use src\Vars as vars;

use src\sqli\SQLi as sqli;

class ClienteService extends Service {

    protected $access = ["MachineClient"];

    /**
     * @machine
     */
    public function get() {

        $query = "SELECT clientes.* 
        FROM clientes JOIN machine
        ON clientes.machine_id = machine.id";

        $machineData = $this->client->getData();
        $machineId   = (int)$machineData['machine_id'];
        $slug = isset($machineData['slug']) ? $machineData['slug'] : false;

        $query .=  $slug ?
                "WHERE clientes.slug = '$slug' AND machine.id = $machineId AND clientes.status = 1" :
                "WHERE machine.id = $machineId AND clientes.status = 1";

        $res = sqli::query($query);

        if($res){
            $this->response = new Response($res->fetchAllAssoc());
            return;
        }
            
        Response::error();

    }


}