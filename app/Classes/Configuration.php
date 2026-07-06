<?php

namespace App\Classes;

use PDO;

class Configuration
{
    private $conn;
    private $table_name = "configurations";

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT name, value FROM " . $this->table_name);
        $configs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $configs;
    }

    public function update($name, $value)
    {
        $stmt = $this->conn->prepare("UPDATE configurations SET value = ? WHERE name = ?");
        return $stmt->execute([$value, $name]);
    }

    public function getAllAsArray()
    {
        $stmt = $this->conn->query("SELECT name, value FROM configurations");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
