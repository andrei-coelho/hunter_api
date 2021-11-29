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

    
        if($res =
            sqli::query(
            "SELECT 
                    rede_social.nome as redeSocial,
                    perfis.nome as nome,
                    perfis.slug as slug,
                    perfis_cliente.status as status,
                    perfis_cliente.follow as follow,
                    perfis_cliente.data_att as dataAtt

            FROM    perfis_cliente
            JOIN    perfis ON perfis_cliente.perfil_id = perfis.id
            JOIN    rede_social ON rede_social.id = perfis.rede_social_id
            JOIN    clientes ON clientes.id = perfis_cliente.cliente_id
                
            WHERE   (perfis_cliente.status = 1 OR perfis_cliente.status = 2)
              AND   perfis.status = 1
              AND   clientes.status = 1
              AND   clientes.slug = '$slug'
            
            ORDER BY perfis_cliente.id ASC;
        ")){
            $this->response = new Response($res->fetchAllAssoc());
            return;
        }
            
        Response::error();

    }

    
    public function getPerfisAncoras(){

        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug']) || !($slug = $vars['clientSlug'])) Response::error();

        if($res =
            sqli::query(
            "SELECT 
                    rede_social.nome as redeSocial,
                    perfis.nome as nome,
                    perfis.slug as slug

            FROM    perfis_ancoras
            JOIN    perfis ON perfis_ancoras.perfil_id = perfis.id
            JOIN    rede_social ON rede_social.id = perfis.rede_social_id
            JOIN    clientes ON clientes.id = perfis_ancoras.cliente_id
                
            WHERE   
                    perfis.status = 1
                AND clientes.status = 1
                AND clientes.slug = '$slug'
            
            ORDER BY perfis_ancoras.id ASC;
        ")){
            $this->response = new Response($res->fetchAllAssoc());
            return;
        }
        
        Response::error();
    }


    public function saveNewProfiles(){

        $vars = vars::get();
        
        if(!$vars 
            || !isset($vars['clientSlug']) 
            || !isset($vars['profiles'])
            || !isset($vars['socialMedia'])
        ) Response::error();

        $smid = $vars['socialMedia'];
        $cliS = $vars['clientSlug'];
        $newP = $vars['profiles'];

        $res1 = sqli::query("SELECT id FROM rede_social WHERE nome = '$smid'");
        if(!$res1) Response::error(500);

        $res2 = sqli::query("SELECT id FROM clientes WHERE slug = '$cliS'");
        if(!$res2) Response::error(500);

        $ids = $res1->fetchAssoc()['id'];
        $idc = $res2->fetchAssoc()['id'];

        $errors   = 0;
        $success  = 0;
        $repetido = 0;

        foreach ($newP as $key => $value) {
            
            $resp = $this->try_insert_profile($ids, $idc, $value['nome'], $value['slug']);
            
            if(is_array($resp)){
                if(!$resp[0] && $resp[1] == "repetido") $repetido++;
                else if(!$resp[0] && $resp[1] == "erro") $errors++;
                else if($resp[0]) $success++;
                continue;
            }

            $resp === true ?
            $success++ :
            $errors++ ;
            
        }

        $this->response = new Response(["success" => $success, "errors" => $errors, "repetidos" => $repetido]);
        
    }


    private function try_insert_profile($smid, $idc, $nome, $slug){

        $slug = str_replace("@", "", $slug);
        $chck = sqli::query("SELECT id FROM perfis WHERE slug = '$slug' AND rede_social_id = $smid");

        if($chck->rowCount() == 0){
            $profileId = sqli::exec(
                "INSERT INTO perfis(nome, slug, rede_social_id, data_att, `status`)
                 VALUES ('$nome', '$slug', $smid, now(), 1)", 
            true);
        } else {
            $profileId = $chck->fetchAssoc()['id'];
        }

        if(!$profileId) return [false, 'erro'];

        if(sqli::query("SELECT id FROM perfis_cliente WHERE cliente_id = $idc AND perfil_id = $profileId")->rowCount() > 0) return [false, 'repetido'];

        return sqli::exec(
            "INSERT INTO 
            perfis_cliente (cliente_id, perfil_id, `status`, data_att)
            VALUES         ($idc, $profileId, 1, now())"
        );

    }


}