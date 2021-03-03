<?php 

namespace service;

use src\client\Client as Client;
use src\Response as Response;

abstract class Service {

    protected $access = "ALL";
    protected $client;
    protected $response;
    protected $slugCliente;

    public function __construct(Client $client, string $slugCliente){
        $this->client = $client;
        $this->slugCliente = $slugCliente;
    }

    public function isValidClient():bool{

        $expl = explode("\\", get_class($this->client));
        $nameType = array_pop($expl);
        return $this->access == "ALL" || in_array($nameType, $this->access);

    }

    public function getResponse():Response {

        if($this->response && $this->response instanceof Response) return $this->response;
        return new Response;
        
    }

}