<?php 

/**
 *
 *           Desenvolvido por:
 *
 *         ┏━━━┓━━┳━━━━┓━━━┓━━┓━┓
 *         ┃ ┏┓┃ ┃┃┃ ┓┓┃┃━┓┃ ┳┛ ┃
 *         ┃ ┣┃┃ ┃┃┃ ┻┛┃┃━┓┃ ┻┓ ┃
 *         ┗━┛┗┛━┻━┛━━━┛━ ┗┗━━┛━┛
 *
 *         andreifcoelho@gmail.com
 *         github.com/andrei-coelho
 *
 *
 * */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Content-Type: application/json');
require "../autoload.php";
src\App::start();