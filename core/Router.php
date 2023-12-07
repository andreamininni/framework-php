<?php

namespace app\core;

use app\core\Request;
use app\core\Response;

class Router {

    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
    }

    public function get($uri, $callback){
        $this->routes['get'][$uri] = $callback;
    }

    public function post($uri, $callback){
        $this->routes['post'][$uri] = $callback;
    }

    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;

        if($callback === false ){
            echo "Not found!";
            exit;
        }

        if(is_array($callback)){
            $callback[0] = new $callback[0]($this->request, $this->response);
        }

        return call_user_func($callback);
    }


}
