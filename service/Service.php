<?php 

namespace service;

use src\Client as Client;
use src\Response as Response;

abstract class Service {

    protected $access = "ALL";
    protected $client;

    public function __construct(Client $client){
        $this->client = $client;
        if(!$this->isValidClient())
            throw new Exception("Erro. Este cliente não é válido!", 1);
    }

    abstract function isValidClient():bool;

    abstract function get():Response;
    abstract function create():Response;
    abstract function update():Response;
    abstract function delete():Response;

}