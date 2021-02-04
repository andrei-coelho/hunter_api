<?php 

namespace src\client;

class ClientFactory {

    private static $clients = [
        'UserClient', 'MachineClient', 'AdminClient'
    ];

    public static function getClient($slugClient):Client{
        
        foreach($_SERVER as $K => $V){
        
            $a = explode('_' , $K);
        
            if(array_shift($a) == 'HTTP'){
               
                if(isset($a[1]) && $a[0]."-".$a[1] == 'HUNTER-CHAVE'){
                    $client = ucfirst(strtolower($a[2]))."Client";
                    if(in_array($client, self::$clients)){
                        $client = "src\\client\\".$client;
                        return new $client($slugClient);
                    }
                }
                
            }
        
        }

        return new DefaultClient();

    }

}