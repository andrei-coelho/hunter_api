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
        $slug = $vars['clientSlug'];

        $res =
        sqli::query(
            "SELECT 
                rede_social.nome as redeSocial,
                perfis.nome as nome,
                perfis.slug as slug,
                perfis_cliente.status as status,
                perfis_cliente.follow as follow,
                perfis_cliente.data_att as dataAtt

            FROM perfis_cliente
            JOIN perfis ON perfis_cliente.perfil_id = perfis.id
            JOIN rede_social ON rede_social.id = perfis.rede_social_id
            JOIN clientes ON clientes.id = perfis_cliente.cliente_id
                
            WHERE   (perfis_cliente.status = 1 OR perfis_cliente.status = 2)
                AND perfis.status = 1
                AND clientes.status = 1
                AND clientes.slug = '$slug'
            
            ORDER BY perfis_cliente.id ASC;
            ");
        
        $values = $res->fetchAllAssoc();

        if($res){
            $this->response = new Response($values);
            return;
        }
            
        Response::error();

    }

}