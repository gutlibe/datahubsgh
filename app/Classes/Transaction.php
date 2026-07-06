<?php

namespace App\Classes;

use PDO;

class Transaction
{
    private $conn;
    private $table_name = "transactions";

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create(array $data)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO " . $this->table_name . " (user_id, reference, principal_amount, fees, total_amount, status, source) VALUES (:user_id, :reference, :amount, 0, :amount, :status, :source)"
            );
            
            $stmt->execute([
                ':user_id' => $data['user_id'] ?? null,
                ':reference' => $data['reference'],
                ':amount' => $data['amount'],
                ':status' => $data['status'],
                ':source' => $data['source']
            ]);

            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            // Log the error
            error_log('Error creating transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByReference(string $reference): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE reference = ?");
        $stmt->execute([$reference]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
