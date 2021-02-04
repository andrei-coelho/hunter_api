<?php 

namespace service;

abstract class ModelService extends Service {

    abstract function get();
    abstract function create();
    abstract function update();
    abstract function delete();

}