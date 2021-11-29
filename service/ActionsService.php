<?php 

namespace service;

use src\Response as Response;
use src\Vars as vars;

use src\sqli\SQLi as sqli;

class ActionsService extends Service { 

    
    protected $access = ["MachineClient"];


    public function getActionsClient(){
        
        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug'])) Response::error();
        
        $id   = $this->client->getData()['machine_id'];
        $slug = $vars['clientSlug'];

        $res = sqli::query(
            "SELECT
                    actions.slug as slug,
                    rede_social.nome as rede_social,
                    actions_cliente.limite
            
            FROM    actions 
            JOIN    actions_cliente ON actions.id = actions_cliente.action_id
            JOIN    rede_social     ON rede_social.id = actions_cliente.rede_social_id
            JOIN    clientes        ON actions_cliente.cliente_id = clientes.id 
        
            WHERE   clientes.slug = '$slug'  

            ORDER BY ordem ASC

            ");
        
        $this->response = new Response((function($res){
            $listFinal   = [];        
            foreach ($res->fetchAllAssoc() as $act) $listFinal[$act['rede_social']][] = $act;
            return $listFinal;
        })($res));
        
    }


    public function follow(){

        $vars = vars::get();
        if( !$vars || 
            !isset($vars['clientSlug']) || 
            !isset($vars['account_id']) ||
            !isset($vars['profileSlug']) 
        ) Response::error();

        $account_id  = (int)$vars['account_id'];
        $clientSlug  = $vars['clientSlug'];
        $profileSlug  = $vars['profileSlug'];
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
                AND clientes.status = 1
        ");

        if($check -> rowCount() == 0){
            Response::error(403, "Você não tem acesso para realizar essa ação");
            return;
        }

        $rede_social = $check->fetchAssoc()['rede_social_id'];

        // salva ação de seguir em um determinado usuário 

        $selProfile = sqli::query(
            "SELECT  perfis_cliente.id,
                     perfis_cliente.status 
               
               FROM  perfis_cliente 
               JOIN  perfis ON perfis_cliente.perfil_id = perfis.id
               JOIN  clientes ON clientes.id = perfis_cliente.cliente_id
               
               WHERE clientes.slug = '$clientSlug' 
                 AND clientes.status = 1
                 AND perfis.slug = '$profileSlug'
                 AND perfis.rede_social_id = $rede_social;"
        );

        if(!$selProfile || $selProfile -> rowCount() == 0){
            Response::error(404, "O perfil que você está tentando seguir não está registrado no banco de dados");
            return;
        }

        $profile = $selProfile->fetchAssoc();

        if($profile['status'] != 1){
            Response::error(403, "Não é possível seguir esse perfil! O perfil que você está tentando seguir já tem seu status modificado");
            return;
        }

        $profileId = $profile['id'];

        if(!sqli::exec("UPDATE perfis_cliente SET `status` = 2 WHERE id = $profileId")){
            Response::error(500, "Ocorreu um erro ao tentar atualizar");
            return;
        }

        //$this->response = new Response([$profile]);

        $this->register_map_action($rede_social, $account_id, $clientSlug, "follow");
    
    }


    private function register_map_action(int $rede_social, int $account_id, $clientSlug, $action_name){
        
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
            return;
        }


    }


    
}
        