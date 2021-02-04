<?php 

namespace service;

use src\client\Client as Client;
use src\Response as Response;

abstract class Service {

    protected $access = "ALL";
    protected $client;
    protected $response;

    public function __construct(Client $client){
        $this->client = $client;
    }

    public function isValidClient():bool{

        $expl = explode("\\", get_class($this->client));
        $nameType = array_pop($expl);
        return $this->access == "ALL" || $nameType == $this->access;

    }

    public function getResponse():Response {
        if($this->response && $this->response instanceof Response){
            return $this->response;
        }
        return new Response;
    }

}