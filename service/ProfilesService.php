<?php 

namespace service;

use src\Response as Response;
use src\Vars as vars;

use src\sqli\SQLi as sqli;

class ProfilesService extends Service { 

    protected $access = ["MachineClient"];

    public function get(){

        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug'])) Response::error();
        $id = $this->client->getData()['machine_id'];

        $res =
        sqli::query(
            "SELECT * 
                FROM perfis_cliente
                JOIN perfis ON perfis_cliente.perfil_id = perfis.id
                WHERE perfis_cliente.status > 0
            ");

        $this->response = new Response($res->fetchAllAssoc());

    }
}