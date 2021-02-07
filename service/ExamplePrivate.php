<?php 

namespace service;
use src\Response as Response;
use src\Vars as vars;

class ExamplePrivate extends ModelService {

    protected $access = ["MachineClient", "UserClient", "AdminClient"];

    public function get():Response {
        return new Response();
    }

    public function create():Response {
        var_dump(vars::get());
        return new Response();
    }

    public function update():Response {
        return new Response();
    }

    public function delete():Response {
        return new Response();
    }

}