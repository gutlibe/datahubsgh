<?php

namespace App\Classes;

use PDO;
use Exception;

class Migrator
{
    private $conn;
    private $migrationPath;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->migrationPath = __DIR__ . '/../../database/migrations';
    }

    public function migrate()
    {
        $this->createMigrationsTable();
        $ranMigrations = $this->getRanMigrations();

        $files = glob($this->migrationPath . '/*.php');
        $migrationsToRun = [];

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');
            if (!in_array($migrationName, $ranMigrations)) {
                $migrationsToRun[] = $file;
            }
        }

        if (empty($migrationsToRun)) {
            echo "Nothing to migrate.\n";
            return;
        }

        foreach ($migrationsToRun as $file) {
            $migrationName = basename($file, '.php');
            echo "Migrating: $migrationName\n";
            
            require_once $file;
            
            $className = $this->getClassName($migrationName);
            
            if (class_exists($className)) {
                $migration = new $className();
                try {
                    $migration->up();
                    $this->logMigration($migrationName);
                    echo "Migrated: $migrationName\n";
                } catch (Exception $e) {
                    echo "Failed: $migrationName. Error: " . $e->getMessage() . "\n";
                    return;
                }
            } else {
                echo "Class $className not found in $file\n";
            }
        }
    }

    private function createMigrationsTable()
    {
        $this->conn->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                ran_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        );
    }

    private function getRanMigrations()
    {
        try {
            $stmt = $this->conn->query("SELECT migration FROM migrations");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }

    private function logMigration($migrationName)
    {
        $batch = $this->getNextBatchNumber();
        $stmt = $this->conn->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migrationName, $batch]);
    }

    private function getNextBatchNumber()
    {
        $stmt = $this->conn->query("SELECT MAX(batch) FROM migrations");
        $maxBatch = $stmt->fetchColumn();
        return $maxBatch ? $maxBatch + 1 : 1;
    }

    private function getClassName($migrationName)
    {
        $name = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationName);
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }
}
