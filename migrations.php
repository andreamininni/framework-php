<?php

use app\core\Application;

require_once __DIR__."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'pwd' => $_ENV['DB_PASSWORD'],
    ],
    'key' => $_ENV['SECRET_KEY']
];

$app = new Application(dirname(__DIR__).'/lezione_fin', $config);


$app->db->applyMigrations();