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

        header("HTTP/1.0 $num");

        $response = new Response(["message" => ($message ?? "Erro $num")], $num, Response::ERROR);
        $response->commit();
    }

    public function commit(){

        echo json_encode($this->response, 
            JSON_PRESERVE_ZERO_FRACTION | 
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }
    
}