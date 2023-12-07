<?php

namespace app\models;

use app\core\Model;
use Firebase\JWT\JWT;
use app\core\Application;

class User extends Model {
    public $name;
    public $email;
    public $password;
    public $passwordConfirmation;
    public $jwt;

    public static function tableName(): string {
        return 'users';
    }


    public function rules(){
        return [
            'name' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_MIN, 'min' => 8
                ]
            ],
            'passwordConfirmation' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'password']
            ],
        ];
    }

    public function save(){
        // $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        // $query = "INSERT INTO " . self::tableName(). " (name, email, password) values ('$this->name','$this->email', '$this->password');";
        // $statement = Application::$app->db->pdo->prepare($query);
        // $statement->execute();
        // return true;
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $statement = Application::$app->db->pdo->prepare("INSERT INTO users (name, email, password) values ( :name, :email, :password );");
        $statement->bindValue(":name", $this->name);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $this->password);
        $statement->execute();
        return true;
    }


    public function generateJWT(){
        $secretKey = Application::$app->getSecret();
        $issuer = "http://localhost"; //! il nostro sito
        $audience = "API-Lesson"; //! a chi e' destinato
        $issued_at = time(); //! quando e' stato emesso
        $notBefore = $issued_at + 10; //! dopo quanti secondi dopo l'emissione puo' essere usato
        $expireAt = $issued_at + 60; //! scade dopo 60 secondi
        $token = array(
            'iss' => $issuer,
            'aud' => $audience,
            'iat' => $issued_at,
            'nbf' => $notBefore,
            'exp' => $expireAt,
            'data' => array(
                'name' => $this->name,
                'email' => $this->email
            )
        );
        $this->jwt = JWT::encode($token, $secretKey, 'HS256');
    }
}