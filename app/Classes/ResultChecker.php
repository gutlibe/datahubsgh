<?php

namespace App\Classes;

use PDO;

/**
 * Manages result checker types, tiered pricing, and orders. Mirrors the
 * Configuration/Order class conventions used elsewhere in this app.
 */
class ResultChecker
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllTypes()
    {
        $stmt = $this->conn->query("SELECT * FROM result_checker_types ORDER BY type_key ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEnabledTypes()
    {
        $stmt = $this->conn->query("SELECT * FROM result_checker_types WHERE enabled = 1 ORDER BY type_key ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findType($checkerType)
    {
        $stmt = $this->conn->prepare("SELECT * FROM result_checker_types WHERE type_key = ? LIMIT 1");
        $stmt->execute([$checkerType]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function setTypeEnabled($checkerType, $enabled)
    {
        $stmt = $this->conn->prepare("UPDATE result_checker_types SET enabled = ? WHERE type_key = ?");
        return $stmt->execute([$enabled ? 1 : 0, $checkerType]);
    }

    public function getTiers($checkerType)
    {
        $stmt = $this->conn->prepare("SELECT * FROM result_checker_price_tiers WHERE checker_type = ? ORDER BY sort_order ASC, min_qty ASC");
        $stmt->execute([$checkerType]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function replaceTiers($checkerType, array $tiers)
    {
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare("DELETE FROM result_checker_price_tiers WHERE checker_type = ?");
            $stmt->execute([$checkerType]);

            $insert = $this->conn->prepare("INSERT INTO result_checker_price_tiers (checker_type, min_qty, max_qty, unit_price, sort_order) VALUES (?, ?, ?, ?, ?)");
            $order = 0;
            foreach ($tiers as $tier) {
                $insert->execute([
                    $checkerType,
                    (int)$tier['min_qty'],
                    ($tier['max_qty'] === '' || $tier['max_qty'] === null) ? null : (int)$tier['max_qty'],
                    (float)$tier['unit_price'],
                    $order++
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function resolveUnitPrice($checkerType, $quantity)
    {
        $tiers = $this->getTiers($checkerType);
        if (empty($tiers)) return null;

        foreach ($tiers as $tier) {
            $min = (int)$tier['min_qty'];
            $max = $tier['max_qty'] !== null ? (int)$tier['max_qty'] : null;
            if ($quantity >= $min && ($max === null || $quantity <= $max)) {
                return (float)$tier['unit_price'];
            }
        }

        return (float)end($tiers)['unit_price'];
    }

    public function createOrder(array $data)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO result_checker_orders (user_id, checker_type, display_name, quantity, msisdn, unit_price, amount, reference, status)
             VALUES (:user_id, :checker_type, :display_name, :quantity, :msisdn, :unit_price, :amount, :reference, :status)"
        );
        $stmt->execute([
            ':user_id' => $data['user_id'] ?? null,
            ':checker_type' => $data['checker_type'],
            ':display_name' => $data['display_name'] ?? null,
            ':quantity' => $data['quantity'],
            ':msisdn' => $data['msisdn'],
            ':unit_price' => $data['unit_price'],
            ':amount' => $data['amount'],
            ':reference' => $data['reference'],
            ':status' => $data['status'] ?? 'pending'
        ]);
        return $this->conn->lastInsertId();
    }

    public function findOrderByReference($reference)
    {
        $stmt = $this->conn->prepare("SELECT * FROM result_checker_orders WHERE reference = ?");
        $stmt->execute([$reference]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateStatus($orderId, $status, $errorMessage = null)
    {
        $stmt = $this->conn->prepare("UPDATE result_checker_orders SET status = ?, error_message = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $errorMessage, $orderId]);
    }

    public function markCompleted($orderId, array $cards)
    {
        $stmt = $this->conn->prepare("UPDATE result_checker_orders SET status = 'completed', cards = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([json_encode($cards), $orderId]);
    }

    /**
     * Calls CK to actually issue the card(s) now that payment is confirmed.
     * Never call this while holding a DB row lock - it's a network request.
     */
    public function processPurchase($orderId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM result_checker_orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order || $order['status'] !== 'processing') {
            return;
        }

        $ck = new CKGodswayService();
        $result = $ck->sendResultChecker($order['checker_type'], $order['msisdn'], (int)$order['quantity'], $order['reference']);

        if (!empty($result['success']) && !empty($result['cards'])) {
            $this->markCompleted($order['id'], $result['cards']);
        } else {
            // Payment is already captured by Paystack and cannot be
            // auto-reversed (no wallet in this app). Left 'failed' for
            // manual admin resolution.
            $this->updateStatus($order['id'], 'failed', 'Provider purchase failed');
        }
    }

    public function getOrders($limit = 30, $offset = 0, $status = '', $searchTerm = '')
    {
        $sql = "SELECT * FROM result_checker_orders WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        if (!empty($searchTerm)) {
            $sql .= " AND (reference LIKE :search OR msisdn LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderCount($status = '', $searchTerm = '')
    {
        $sql = "SELECT COUNT(*) FROM result_checker_orders WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        if (!empty($searchTerm)) {
            $sql .= " AND (reference LIKE :search OR msisdn LIKE :search)";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
