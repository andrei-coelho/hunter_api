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

        $query = "SELECT cliente.* 
        FROM cliente JOIN machine
        ON cliente.machine = machine.id ";

        $machineData = $this->client->getData();
        $machineId   = (int)$machineData['machine_id'];
        $slug = isset($machineData['slug']) ? $machineData['slug'] : false;

        $query .=  $slug ?
                "WHERE cliente.slug = '$slug' AND machine.id = $machineId" :
                "WHERE machine.id = $machineId";

        $res = sqli::query($query);

        if($res){
            $this->response = new Response($res->fetchAllAssoc());
            return;
        }
            
        Response::error();

    }

}