<?php 

namespace service;

use src\Response as Response;
use src\Vars as vars;

use src\sqli\SQLi as sqli;

class AccountsService extends Service { 

    protected $access = ["MachineClient"];

    public function get(){
        
        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug'])) Response::error();
        $id = $this->client->getData()['machine_id'];

        $res = 
        sqli::query(

            "SELECT 
                contas_rede_social.email as email,
                contas_rede_social.senha as senha,
                perfis.nome as nome,
                perfis.slug as slug

             FROM contas_rede_social
             JOIN perfis_cliente ON perfis_cliente.id = contas_rede_social.perfil_cliente_id
             JOIN perfis ON perfis_cliente.perfil_id = perfis.id 
             JOIN clientes ON perfis_cliente.cliente_id = clientes.id 
             JOIN machine ON clientes.machine_id = machine.id
             JOIN rede_social ON perfis.rede_social_id = rede_social.id

             WHERE 
                    machine.id = $id 
                AND rede_social.status = 1
                AND perfis.status = 1
                AND clientes.slug = '".$vars['clientSlug']."'
            ");
        
        $this->response = new Response($res -> fetchAllAssoc());

    }

}