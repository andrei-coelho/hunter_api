<?php 

namespace src;

class Response {

    public const SUCCESS = "success", PARTIAL = "partial", ERROR = "error";
    private $response;

    public function __construct(array $arr = [], int $httpResponse = 200, $status = Response::SUCCESS){
       
        $this->response =  [
            "code"   => $httpResponse,
            "data"   => $arr,
            "status" => $status
        ];
    
    }
 
    public static function error(int $num = 404, $message = null){

        $response = new Response(["message" => ($message ?? "Erro $num")], $num, Response::ERROR);
        $response->commit($num);

    }

    public function commit($status = "200 OK"){
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
        }
        
        header("HTTP/1.1 $status");

        echo json_encode($this->response, 
            JSON_PRESERVE_ZERO_FRACTION | 
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }
    
}