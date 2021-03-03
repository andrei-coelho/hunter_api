<?php 

namespace service;
use src\Response as Response;
use src\Vars as vars;

class ExamplePrivate extends Service {

    protected $access = ["MachineClient", "UserClient", "AdminClient"];

    public function get() {
        $this->response = new Response(vars::get());
    }

    public function create() {
        $this->response = new Response();
    }

    public function update() {
        $this->response = new Response();
    }

    public function delete() {
        $this->response = new Response();
    }

}