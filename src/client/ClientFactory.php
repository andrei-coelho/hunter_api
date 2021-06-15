<?php 

namespace src\client;

use src\Vars as vars;

class ClientFactory {

    public static function getClient():Client{
        
        $clie = false;
        $vars = vars::get();
        
        foreach($vars as $K => $V){
        
            $a = explode('-' , $K);
            
            if(isset($a[1]) && strtoupper($a[0]."-".$a[1]) == 'HUNTER-CHAVE'){
                $client = ucfirst(strtolower($a[2]))."Client";
                $client = "src\\client\\".$client;
                $clie   = new $client($V);
                continue;
            }
        
        }
        
        if($clie) return $clie;

        return new DefaultClient("default_key_gen_temp_".md5(mt_rand(0,100000).date('dmYhis')));

    }

}