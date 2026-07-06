<?php

namespace App\Classes;

use PDO;

class Order
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create(array $data)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO orders (user_id, product_id, reference, msisdn, amount, network, volume, product_name, status, mode, source) 
             VALUES (:user_id, :product_id, :reference, :msisdn, :amount, :network, :volume, :product_name, :status, :mode, :source)"
        );
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function updateStatus(int $orderId, string $status, string $message = '')
    {
        $stmt = $this->conn->prepare(
            "UPDATE orders SET status = ?, status_message = ?, status_updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([$status, $message, $orderId]);
    }

    public function findOrderByIdForProcessing(int $orderId): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findOrderByReference(string $reference): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE reference = ?");
        $stmt->execute([$reference]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getByReference(string $reference): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE reference = ?");
        $stmt->execute([$reference]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrder(int $orderId, array $data)
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
        }
        $stmt = $this->conn->prepare(
            "UPDATE orders SET " . implode(', ', $updates) . " WHERE id = :id"
        );
        $data['id'] = $orderId;
        return $stmt->execute($data);
    }

    public function findByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT o.*, p.name as product_name, p.customer_price as product_price FROM orders o JOIN products p ON o.product_id = p.id WHERE o.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll($mode = 'auto')
    {
        $sql = "SELECT o.*, u.first_name, u.last_name, p.name as product_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                LEFT JOIN products p ON o.product_id = p.id 
                WHERE o.mode = ? 
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$mode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalSales()
    {
        $stmt = $this->conn->query("SELECT SUM(amount) FROM orders WHERE status != 'pending'");
        return $stmt->fetchColumn();
    }

    public function getSalesByPeriod($period)
    {
        $sql = "SELECT SUM(amount) FROM orders WHERE created_at >= ? AND status != 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$period]);
        return $stmt->fetchColumn();
    }

    public function getNetworkStats()
    {
        $stmt = $this->conn->query("SELECT network, COUNT(*) as count FROM orders WHERE status != 'pending' GROUP BY network");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatusStats()
    {
        $stmt = $this->conn->query("SELECT status, COUNT(*) as count FROM orders WHERE status != 'pending' GROUP BY status");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersByPhoneNumber($phoneNumber)
    {
        $sql = "SELECT o.*, u.phone_number as user_phone_number 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE u.phone_number = ? 
                ORDER BY o.created_at DESC 
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phoneNumber]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrders($limit = 15, $offset = 0, $status = '', $searchTerm = '', $provider = '', $network = '', $mode = '')
    {
        $sql = "SELECT o.*, p.name as product_name FROM orders o 
                LEFT JOIN products p ON o.product_id = p.id 
                WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND o.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($provider)) {
            $sql .= " AND o.provider = :provider";
            $params[':provider'] = $provider;
        }

        if (!empty($network)) {
            $sql .= " AND o.network = :network";
            $params[':network'] = $network;
        }

        if (!empty($mode)) {
            $sql .= " AND o.mode = :mode";
            $params[':mode'] = $mode;
        }

        if (!empty($searchTerm)) {
            $sql .= " AND (o.reference LIKE :searchTerm OR o.msisdn LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if (!empty($status)) {
            $stmt->bindValue(':status', $params[':status']);
        }
        if (!empty($provider)) {
            $stmt->bindValue(':provider', $params[':provider']);
        }
        if (!empty($network)) {
            $stmt->bindValue(':network', $params[':network']);
        }
        if (!empty($mode)) {
            $stmt->bindValue(':mode', $params[':mode']);
        }
        if (!empty($searchTerm)) {
            $stmt->bindValue(':searchTerm', $params[':searchTerm']);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ensure default values for potentially NULL fields
        foreach ($results as &$result) {
            $result['product_name'] = $result['product_name'] ?? $result['product_name'] ?? '';
            $result['source'] = $result['source'] ?? 'Direct';
            $result['status'] = $result['status'] ?? 'pending';
            $result['provider'] = $result['provider'] ?? 'N/A';
        }
        
        return $results;
    }

    public function getOrderCount($status = '', $searchTerm = '', $provider = '', $network = '', $mode = '')
    {
        $sql = "SELECT COUNT(*) FROM orders o WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND o.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($provider)) {
            $sql .= " AND o.provider = :provider";
            $params[':provider'] = $provider;
        }

        if (!empty($network)) {
            $sql .= " AND o.network = :network";
            $params[':network'] = $network;
        }

        if (!empty($mode)) {
            $sql .= " AND o.mode = :mode";
            $params[':mode'] = $mode;
        }

        if (!empty($searchTerm)) {
            $sql .= " AND (o.reference LIKE :searchTerm OR o.msisdn LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
