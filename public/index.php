<?php

use app\core\Application;
use app\controllers\AuthController;
use app\controllers\ArticleController;

require_once __DIR__."/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'pwd' => $_ENV['DB_PASSWORD'],
    ],
    'key' => $_ENV['SECRET_KEY']
];

$app = new Application(dirname(__DIR__), $config);

$app->router->post('/login', [AuthController::class, 'login']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->post('/article/store', [ArticleController::class, 'store']);

$app->run();