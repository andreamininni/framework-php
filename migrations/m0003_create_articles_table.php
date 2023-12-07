<?php
namespace app\migrations;
use app\core\Application;
class m0003_create_articles_table{
    public function up(){
        $db = Application::$app->db;
        $db->pdo->exec("CREATE TABLE articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            body TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }
    public function down(){
        $db = Application::$app->db;
        $db->pdo->exec("DROP TABLE articles");
    }
}
?>