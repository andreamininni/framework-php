<?php

namespace app\core;

use app\core\Router;
use app\core\Request;
use app\core\Database;
use app\core\Response;

class Application {
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public static string $ROOT_DIR;
    public static Application $app;
    private $key;
    public function __construct($root, $config){
        $this->key = $config['key'];
        self::$app = $this;
        self::$ROOT_DIR = $root;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }

    public function getSecret(){
        return $this->key;
    }

    public function run(){
        echo $this->router->resolve();
    }
}