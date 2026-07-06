<?php

namespace App\Classes;

use PDO;

class Product
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM products WHERE is_available = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProductsForAdmin()
    {
        $stmt = $this->conn->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByNetwork(string $network)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE network = ? AND is_available = 1");
        $stmt->execute([$network]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (name, network, volume, customer_price, agent_price, validity, is_available) VALUES (:name, :network, :volume, :customer_price, :agent_price, :validity, :is_available)"
        );

        $stmt->execute([
            ':name' => $data['name'],
            ':network' => $data['network'],
            ':volume' => $data['volume'],
            ':customer_price' => $data['customer_price'],
            ':agent_price' => $data['agent_price'],
            ':validity' => $data['validity'],
            ':is_available' => $data['is_available'] ?? true,
        ]);

        return $this->conn->lastInsertId();
    }

    public function update(int $id, array $data)
    {
        // Remove 'id' from the data array if it exists, as it's handled separately in the WHERE clause
        unset($data['id']);

        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = :$key";
        }
        $setClauseString = implode(', ', $setClauses);

        $stmt = $this->conn->prepare(
            "UPDATE products SET $setClauseString WHERE id = :id"
        );

        // Add the ID for the WHERE clause
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function bulkUpdateAvailability(array $ids, bool $isAvailable)
    {
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->conn->prepare("UPDATE products SET is_available = ? WHERE id IN ($in)");
        $params = array_merge([$isAvailable], $ids);
        return $stmt->execute($params);
    }

    public function bulkDelete(array $ids)
    {
        // Check if any of the products are in use
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE product_id IN ($in)");
        $stmt->execute($ids);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new \Exception('One or more products cannot be deleted because they are associated with existing orders.');
        }

        // If not in use, proceed with deletion
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id IN ($in)");
        return $stmt->execute($ids);
    }
}
