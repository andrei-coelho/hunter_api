<?php 

namespace service;
use src\Response as Response;

class ExamplePrivate extends ModelService {

    protected $access = "UserClient";

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