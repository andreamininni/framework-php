<?php

namespace app\models;

use app\core\Model;
use app\models\User;
use app\core\Application;

class Login extends Model {
    public $email;
    public $password;

    public static function tableName(): string {
        return 'users';
    }


    public function rules(){
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_MIN, 'min' => 8
                ]
            ]
        ];
    }

    public function login(){

        $statement = Application::$app->db->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->bindValue(":email", $this->email);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if(!$user) {
            $this->addError('email', 'User does not exists with this email');
            return false;
        }
        if(!password_verify($this->password, $user->password)){
            $this->addError('password', 'Password doesn\'t match');
            return false;
        }

        return $user;

    }
}