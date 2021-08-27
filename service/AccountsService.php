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
                    contas_rede_social.id as conta_id,
                    contas_rede_social.email as email,
                    contas_rede_social.senha as senha,
                    perfis.nome as nome,
                    perfis.slug as slug,
                    rede_social.nome as redeSocial,
                    perfis_cliente.id as id,
                    phone_numbers.phone as phone,
                    ip_adress.ip as ip

            FROM         contas_rede_social
             JOIN        perfis_cliente ON perfis_cliente.id = contas_rede_social.perfil_cliente_id
             JOIN        perfis ON perfis_cliente.perfil_id = perfis.id 
             JOIN        clientes ON perfis_cliente.cliente_id = clientes.id 
             JOIN        machine ON clientes.machine_id = machine.id
             JOIN        rede_social ON perfis.rede_social_id = rede_social.id
             LEFT JOIN   phone_numbers ON phone_numbers.id = contas_rede_social.phone_number_id
             LEFT JOIN   ip_adress ON ip_adress.id = contas_rede_social.IP_adress_id

             WHERE 
                    machine.id = $id 
                AND rede_social.status = 1
                AND perfis.status = 1
                AND clientes.slug = '".$vars['clientSlug']."'
            ");
        
        if(!$res) Response::error();

        $accs = $res -> fetchAllAssoc();
        $in = "(";
        foreach ($accs as $acc) $in .= $acc['conta_id'].",";
        $in = substr($in, 0, -1).")";

        $getAllActionsDay = sqli::query(
            "SELECT 
                    map_actions_day.conta_rede_social_id as conta_id,
                    map_actions_day.count as total,
                    map_actions_day.data as data,
                    actions.slug as action_name,
                    rede_social.nome as rede_social
            FROM    map_actions_day 
            JOIN    actions ON actions.id = map_actions_day.action_id
            JOIN    rede_social ON rede_social.id = actions.rede_social_id
            WHERE   map_actions_day.conta_rede_social_id IN $in
            ");
        
        if($getAllActionsDay->rowCount() > 0){

            $mapActions = $getAllActionsDay->fetchAllAssoc();

            foreach ($accs as $k => $acc) {
                foreach ($mapActions as $action) {
                    if($action['conta_id'] == $acc['conta_id']){
                        $accs[$k]['actions_today'][] = $action;
                    }
                }
            }

        }

        $this->response = new Response($accs);
        
    }



    public function getAccount(){
        
        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug']) || !isset($vars['accountSlug']) || !isset($vars['socialMediaSlug'])) Response::error();
        $id = $this->client->getData()['machine_id'];

        $slug = $vars['clientSlug'];
        $account = $vars['accountSlug'];
        $socialMedia = $vars['socialMediaSlug'];

        $res = sqli::query(
            "SELECT 
                contas_rede_social.email as email,
                contas_rede_social.senha as senha,
                perfis.nome as nome,
                perfis.slug as slug,
                perfis.status as statusPerfil,
                rede_social.nome as redeSocial,
                rede_social.status as statusRedeSocial,
                perfis_cliente.id as id,
                phone_numbers.phone as phone,
                ip_adress.ip as ip
            
            FROM         contas_rede_social
             JOIN        perfis_cliente ON perfis_cliente.id = contas_rede_social.perfil_cliente_id
             JOIN        perfis ON perfis_cliente.perfil_id = perfis.id 
             JOIN        clientes ON perfis_cliente.cliente_id = clientes.id 
             JOIN        machine ON clientes.machine_id = machine.id
             JOIN        rede_social ON perfis.rede_social_id = rede_social.id
             LEFT JOIN   phone_numbers ON phone_numbers.id = contas_rede_social.phone_number_id
             LEFT JOIN   ip_adress ON ip_adress.id = contas_rede_social.IP_adress_id

            WHERE 
                    machine.id = $id 
                AND perfis.slug = '$account' 
                AND clientes.slug = '$slug'
                AND rede_social.nome = '$socialMedia'
                AND clientes.status = 1
        ");
        
        if($res && $res->count() > 0){
            
            $acc = $res -> fetchAllAssoc();

            if($acc[0]['statusRedeSocial'] != 1){
                Response::error(404, "A rede social desta conta está desativada no momento.");
                return;
            }

            if($acc[0]['statusPerfil'] != 1){
                Response::error(404, "A conta está bloqueada ou foi excluída.");
                return;
            }

            $this->response = new Response($acc);
            return;

        }

        Response::error();
    }


    public function saveAction(){

        $vars = vars::get();
        if( !$vars || 
            !isset($vars['clientSlug']) || 
            !isset($vars['account_id']) ||
            !isset($vars['action_name'])
        ) Response::error();

        $account_id  = (int)$vars['account_id'];
        $action_name = $vars['action_name'];
        $clientSlug  = $vars['clientSlug'];
        $machine_id  = $this->client->getData()['machine_id'];

        $check = sqli::query(
            "SELECT
                  contas_rede_social.id,
                  rede_social.id as rede_social_id

            FROM  contas_rede_social
            JOIN  perfis_cliente ON perfis_cliente.id = contas_rede_social.perfil_cliente_id 
            JOIN  clientes ON clientes.id = perfis_cliente.cliente_id 
            JOIN  perfis ON perfis.id = perfis_cliente.perfil_id
            JOIN  rede_social ON perfis.rede_social_id = rede_social.id
            
            WHERE 
                      clientes.machine_id = $machine_id 
                  AND contas_rede_social.id = $account_id 
                  AND clientes.slug = '$clientSlug'
        ");

        if($check -> rowCount() == 0){
            Response::error(403, "Você não tem acesso para realizar essa ação");
            return;
        }

        $rede_social = $check->fetchAssoc()['rede_social_id'];
        
        $actionsClient = sqli::query(
            "SELECT 
                    actions.slug, 
                    actions_cliente.limite,
                    actions.id

            FROM    actions_cliente 
            JOIN    actions ON  actions_cliente.action_id = actions.id 
            JOIN    rede_social ON actions.rede_social_id = rede_social.id
            JOIN    clientes ON actions_cliente.cliente_id = clientes.id 

            WHERE   clientes.slug = '$clientSlug' AND rede_social.id = $rede_social
        ");

        if(!$actionsClient || $actionsClient -> rowCount() == 0){
            Response::error(403, "Não há uma lista de ações cadastradas no cliente.");
            return;
        }

        $actions = $actionsClient->fetchAllAssoc();
    
        if(!($k = array_search($action_name, array_column($actions, "slug")))){
            Response::error(403, "Esta ação não é permitida.");
            return;
        }

        $actionSel = $actions[$k];
        $hoje = date("Y-m-d");

        $mapActions = sqli::query(
            "SELECT 
                 map_actions_day.count as total,
                 map_actions_day.id

            FROM 
                  map_actions_day
            JOIN  contas_rede_social ON map_actions_day.conta_rede_social_id = contas_rede_social.id
            JOIN  actions ON map_actions_day.action_id = actions.id
            JOIN  rede_social ON actions.rede_social_id = rede_social.id
            
            WHERE map_actions_day.data = '$hoje'
            AND   contas_rede_social.id = $account_id 
            AND   actions.slug = '$action_name'
            AND   rede_social.id = $rede_social
        ");

        if($mapActions -> rowCount() > 0){
           
            $actionAccount = $mapActions->fetchAssoc();

            if($actionSel['limite'] <= $actionAccount['total']){
                Response::error(400, "O limite diario permitido ja foi realizado.");
                return;
            }

            $newVal = $actionAccount['total'] + 1;
            $idAcc  = $actionAccount['id'];

            if(!sqli::exec("UPDATE map_actions_day SET `count` = $newVal WHERE id = $idAcc")){
                Response::error(500, "Ocorreu um erro ao tentar atualizar");
            }
            return;
        }

        $actionId = $actionSel['id'];

        if(!sqli::exec("INSERT INTO 
            map_actions_day (`data`, action_id, conta_rede_social_id, `count`)
            VALUES
            ('$hoje', $actionId, $account_id, 1)
        ")){
            Response::error(500, "Ocorreu um erro ao tentar atualizar");
        }


    }


}