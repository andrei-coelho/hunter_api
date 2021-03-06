<?php 

namespace src\client;

abstract class DataClient {

    protected $dataClient;

    public function getData(){
        return $this->dataClient;
    }

}