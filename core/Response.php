<?php

namespace app\core;

class Response {
    public function setStatusCode($code){
        http_response_code($code);
    }

    public function setHeader($value){
        header($value, true);
    }
}