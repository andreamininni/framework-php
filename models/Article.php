<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class Article extends Model {
    public $title;
    public $body;


    public static function tableName(): string {
        return 'articles';
    }


    public function rules(){
        return [
            'title' => [self::RULE_REQUIRED],
            'body' => [self::RULE_REQUIRED]
        ];
    }

    public function save(){
        $statement = Application::$app->db->pdo->prepare("INSERT INTO articles (title, body) values ( :title, :body );");
        $statement->bindValue(":title", $this->title);
        $statement->bindValue(":body", $this->body);
        $statement->execute();
        return true;
    }


}