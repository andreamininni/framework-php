<?php
namespace app\controllers;

use app\core\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\core\Response;
use app\core\Application;

abstract class Controller {

    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
    }
    public function checkJWT(){

        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $jwt = str_replace("Bearer ", '', $_SERVER['HTTP_AUTHORIZATION']);
            $apiSecret = Application::$app->getSecret();
            try {
                $decoded = JWT::decode($jwt, new Key($apiSecret, 'HS256'));
                return true;
            } catch ( \Exception $e){
                if($e->getMessage()== 'Expired token'){
                    echo json_encode(array(
                        "message" => "Expired token",
                        "error" => $e->getMessage()
                    ));
                }
            }
        } else {
            $this->response->setStatusCode(401);
            echo json_encode(array("message" => "Non sei autorizzato"));
        }
    }
}