<?php 

namespace src;

class Vars {

    private static $instance; 
    private $posts;

    private function __construct(){

         if(($inputs = json_decode(file_get_contents('php://input'), true)) != null)
            $this->posts = $inputs;
        
        foreach ($_POST as $key => $value)
            $this->posts[$key] = $value;
        
        // var_dump($_POST);
    }

    public static function get($key = false){
        if(!self::$instance)
            self::$instance = new self();

        $posts = self::$instance->posts ?? [];

        return  $key ? 
                isset($posts[$key]) ? 
                $posts[$key] : 
                [] : 
                $posts;
    }

}