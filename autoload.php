<?php 

require "src/Config.php";
require "src/Spyc.php";

src\Config::create();

spl_autoload_register(function($class) {

    $file = "../".str_replace("\\", "/", $class).".php";
    
    if(in_array($file, [
        "src/Config.php",
        "src/Spyc.php"
    ])) return;

    if(file_exists($file) && !class_exists($class)){
        include $file;
    } else {
        throw new Exception("O arquivo $file não existe ou a Classe $class já foi carregada", 1);
    }

});