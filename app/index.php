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
header('Content-Type: application/json');
require "../autoload.php";
src\App::start();