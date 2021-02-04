<?php 

// {object}/{function}/{client.slug}

namespace src;

class App {

    private static $request;

    private function __construct(){}
    private function __clone(){}

    public static function start() {
        // pega a requisição 

        if(!isset($_GET['req'])) Response::error();
        $req = explode('/', $_GET['req']);
        
        if(!($service = ucfirst(trim($req[0])) ?? false)){
            Response::error();
        }

        if(!($method = trim($req[1]) ?? false)){
            Response::error();
        }
        
        $slugCli = trim($req[2]) ?? false;

        try {
        
            $client = client\ClientFactory::getClient($slugCli);

        } catch (\Throwable $th) {
            Response::error();
        }
        

    }



}