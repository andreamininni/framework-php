<?php
namespace app\controllers;
use app\core\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\core\Response;
use app\models\Article;
use app\core\Application;

class ArticleController extends Controller {

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function store()
    {
        if($this->checkJWT()){
            $body = json_decode(file_get_contents('php://input', true));
            $article = new Article();
            $article->loadData($body);
            if($article->validate() && $article->save()){
                $this->response->setStatusCode(200);
                echo 'article succesfully created';
            } else {
                $this->response->setStatusCode(400);
                echo json_encode($article->errors);
            }
        }
    }
}