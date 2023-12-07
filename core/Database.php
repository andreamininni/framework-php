<?php

namespace app\core;

class Database {
    public \PDO $pdo;

    public function __construct($config){
        $this->pdo = new \PDO($config['dsn'], $config['user'], $config['pwd']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations(){
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations');

        $toApplyMigrations = array_diff($files, $appliedMigrations);

        $newMigrations = [];

        foreach ($toApplyMigrations as $migration) {

            if($migration === '.' || $migration === '..') {continue;}

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $className = "app\migrations\\".$className;
            $instance = new $className();
            $this->log("Applying migration $migration" . PHP_EOL);
            $instance->up();
            $this->log("Migration applied $migration" . PHP_EOL);

            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable(){
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;"
        );
    }

    public function getAppliedMigrations(){
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations( array $migrations ){
        $values = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) values $values");
        $statement->execute();
    }

    public function log($message){
        echo '['.date('Y-m-d H:i:s').'] - '. $message. PHP_EOL;
    }
}