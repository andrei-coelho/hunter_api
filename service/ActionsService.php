<?php 

namespace service;

use src\Response as Response;
use src\Vars as vars;

use src\sqli\SQLi as sqli;

class ActionsService extends Service { 

    protected $access = ["MachineClient"];

    public function getMapActionsDay(){
        
        $vars = vars::get();
        if(!$vars || !isset($vars['clientSlug'])) Response::error();
        
        $id   = $this->client->getData()['machine_id'];
        $slug = $vars['clientSlug'];

        $res = sqli::query(
            "SELECT
                    actions.slug as slug
            
            FROM    actions 
            JOIN    actions_cliente ON actions.id = actions_cliente.action_id
            JOIN    clientes        ON actions_cliente.cliente_id = clientes.id 
        
            WHERE   clientes.slug = '$slug'  
            ");
        
        $str = "(";
        $listActions = $res->fetchAllAssoc();
        foreach ($listActions as $action)  $str .= " actions.slug = '".$action['slug']."' OR ";
        $str = substr($str, 0, -3).")";

        $hoje = date('Y-m-d');
        
        $res2 = sqli::query(
            "SELECT 
                    map_actions_day.count,
                    actions.slug

            FROM    map_actions_day 
            JOIN    clientes ON map_actions_day.cliente_id = clientes.id 
            JOIN    actions  ON map_actions_day.action_id  = actions.id 
            
            WHERE   clientes.slug = '$slug' AND data = '$hoje' AND $str
            ");

        $actionsDay = $res2->fetchAllAssoc();
        $resp = $actionsDay;
        
        if(count($resp) != count($listActions)){
            $usedToDay = array_column($resp, "slug");
            foreach ($listActions as $action) {
                if(!in_array($action['slug'], $usedToDay))
                $resp[] = [
                    "count" => 0,
                    "slug"  => $action['slug']
                ];
            }
        }

        $this->response = new Response($resp);

    }

}
        