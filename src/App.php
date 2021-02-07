<?php 

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
        
        $slugCli = isset($req[2]) ? trim($req[2]) : "";

        sqli\DataBase::open_links();
        
        try {
            $client = client\ClientFactory::getClient($slugCli);
        } catch (\Exception $e) {
            Response::error(403, "Você não é um cliente autorizado");
        }

        $serviceComplete = "service\\".$service;
        
        try {
            $serviceObj = new $serviceComplete($client, $slugCli);
        } catch (\Exception $e) {
            Response::error(404, "Serviço '$service' não existe");
        }

        self::runServiceAndResponse($serviceObj, $method);
        
    }


    private static function runServiceAndResponse(\service\Service $service, $method){
        
        if(!$service->isValidClient()){
            Response::error(403, "Você não tem autorização para acessar este serviço");
        }

        if(!method_exists($service, $method)){
            Response::error(404, "Método '$method' não existe");
        }
        
        $service->$method();
        $response = $service->getResponse();
        sqli\DataBase::close_all();
        $response->commit();

    }



}