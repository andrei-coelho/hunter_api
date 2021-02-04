<?php 

namespace service;
use src\Response as Response;

class Example implements Service {

    private $access = "UserClient";

    public function isValidClient():bool{
        return true;
    }

    public function get():Response {
        return new Response();
    }

    public function create():Response {
        return new Response();
    }

    public function update():Response {
        return new Response();
    }

    public function delete():Response {
        return new Response();
    }

}