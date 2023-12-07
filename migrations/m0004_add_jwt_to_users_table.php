<?php
namespace app\migrations;
use app\core\Application;

class m0004_add_jwt_to_users_table {
    public function up(){
        $db = Application::$app->db;
        $SQL = "ALTER TABLE users ADD jwt VARCHAR(255);";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = Application::$app->db;
        $SQL = "ALTER TABLE users DROP jwt;";
        $db->pdo->exec($SQL);
    }
}