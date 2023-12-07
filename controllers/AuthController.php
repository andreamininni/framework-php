<?php

namespace app\controllers;

use app\models\User;
use app\core\Request;
use app\models\Login;
use app\core\Response;

class AuthController extends Controller{

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function login(){
        $data = json_decode(file_get_contents('php://input', true));
        $login = new Login();
        $login->loadData($data);
        if($login->validate()){
            $user = $login->login();
            if($user){
                $this->response->setStatusCode(200);
                $user->generateJWT();
                echo $user->jwt;
            } else {
                $this->response->setStatusCode(404);
                echo json_encode(array("message" => $login->errors));
            }
        } else {
            $this->response->setStatusCode(500);
            echo json_encode(array("message" => $login->errors));
        }
    }

    public function register(){
        $data = json_decode(file_get_contents('php://input', true));
        $user = new User();
        $user->loadData($data);

        if($user->validate() && $user->save()){
            $message = "User Created Succesfully";
            $this->response->setStatusCode(201);
            $this->response->setHeader("Content-Type: application/json,charset=UTF-8");
            echo json_encode(array("message" => $message));
        } else {
            $this->response->setStatusCode(500);
            echo json_encode($user->errors);
        }
    }
}