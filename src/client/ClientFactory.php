<?php 

namespace src\client;

class ClientFactory {

    public static function getClient($slugClient):Client{
        
        foreach($_SERVER as $K => $V){
        
            $a = explode('_' , $K);
            
            if(array_shift($a) == 'HTTP'){

                if(isset($a[1]) && strtoupper($a[0]."-".$a[1]) == 'HUNTER-CHAVE'){
                    $client = ucfirst(strtolower($a[2]))."Client";
                    $client = "src\\client\\".$client;
                    return new $client($slugClient, $V);
                }
                
            }
        
        }

        return new DefaultClient($slugClient);

    }

}