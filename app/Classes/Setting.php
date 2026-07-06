<?php

namespace App\Classes;

use App\Classes\Database;
use PDO;

class Setting
{
    private $conn;
    private $table_name = "settings";

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getSetting($key)
    {
        $query = "SELECT setting_value FROM " . $this->table_name . " WHERE setting_key = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $key);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['setting_value'] : null;
    }

    public function getAllSettings()
    {
        $query = "SELECT setting_key, setting_value FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    public function setSetting($key, $value)
    {
        $query = "INSERT INTO " . $this->table_name . " (setting_key, setting_value) VALUES (?, ?) " .
                 "ON DUPLICATE KEY UPDATE setting_value = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $key);
        $stmt->bindParam(2, $value);
        $stmt->bindParam(3, $value);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
