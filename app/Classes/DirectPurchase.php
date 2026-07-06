<?php

namespace App\Classes;

use PDO;

class DirectPurchase
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create(int $transactionId, int $productId, string $msisdn, string $network, string $mode = 'manual'): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO direct_purchases (transaction_id, product_id, msisdn, network, mode) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$transactionId, $productId, $msisdn, $network, $mode]);
        return (int)$this->conn->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM direct_purchases WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByTransactionId(int $transactionId): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM direct_purchases WHERE transaction_id = ?');
        $stmt->execute([$transactionId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByReference(string $reference): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT dp.* FROM direct_purchases dp
             JOIN transactions t ON dp.transaction_id = t.id
             WHERE t.reference = ?'
        );
        $stmt->execute([$reference]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getAll(int $limit = 10, int $offset = 0, string $status = ''): array
    {
        $sql = 'SELECT dp.*, t.reference, p.name as product_name 
                FROM direct_purchases dp
                JOIN transactions t ON dp.transaction_id = t.id
                JOIN products p ON dp.product_id = p.id';
        
        if (!empty($status)) {
            $sql .= ' WHERE dp.status = :status';
        }

        $sql .= ' ORDER BY dp.created_at DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->conn->prepare($sql);

        if (!empty($status)) {
            $stmt->bindValue(':status', $status);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount(string $status = ''): int
    {
        $sql = 'SELECT COUNT(*) FROM direct_purchases';
        if (!empty($status)) {
            $sql .= ' WHERE status = :status';
        }
        $stmt = $this->conn->prepare($sql);
        if (!empty($status)) {
            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE direct_purchases SET status = ? WHERE id = ?'
        );
        return $stmt->execute([$status, $id]);
    }

    public function updateMode(int $id, string $mode): bool
    {
        $stmt = $this->conn->prepare(
            'UPDATE direct_purchases SET mode = ? WHERE id = ?'
        );
        return $stmt->execute([$mode, $id]);
    }

    public function getTotalSales(): float
    {
        $stmt = $this->conn->query(
            'SELECT SUM(p.customer_price) 
             FROM direct_purchases dp
             JOIN products p ON dp.product_id = p.id'
        );
        return (float)$stmt->fetchColumn();
    }

    public function getSalesByPeriod(string $startDate, string $endDate = null): float
    {
        $sql = 'SELECT SUM(p.customer_price) 
                FROM direct_purchases dp
                JOIN products p ON dp.product_id = p.id
                WHERE dp.created_at >= :start_date';
        
        if ($endDate) {
            $sql .= ' AND dp.created_at <= :end_date';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':start_date', $startDate);
        if ($endDate) {
            $stmt->bindValue(':end_date', $endDate);
        }
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    public function getNetworkStats(): array
    {
        $stmt = $this->conn->query(
            'SELECT network, COUNT(*) as count 
             FROM direct_purchases 
             GROUP BY network'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatusStats(): array
    {
        $stmt = $this->conn->query(
            'SELECT status, COUNT(*) as count 
             FROM direct_purchases 
             GROUP BY status'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
