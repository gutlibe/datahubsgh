<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Database;
use App\Classes\Setting;
use App\Classes\Order;

header('Content-Type: application/json');

// Ensure only admin can access this (you might want to add proper admin auth check here)
// For now, I'll assume it's protected by the admin middleware if called from admin panel

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['reference'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$reference = htmlspecialchars($data['reference']);

$setting = new Setting();
$paystack_secret_key = $setting->getSetting('paystack_secret_key');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . rawurlencode($reference));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $paystack_secret_key,
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['success' => false, 'message' => 'Error connecting to Paystack']);
    exit;
}

$result = json_decode($response, true);

if ($result['status'] && $result['data']['status'] === 'success') {
    $pdo = Database::getInstance()->getConnection();

    try {
        $pdo->beginTransaction();

        // Get the transaction
        $stmt = $pdo->prepare('SELECT * FROM transactions WHERE reference = :reference FOR UPDATE');
        $stmt->bindValue(':reference', $reference);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            // Update the transaction if not already credited
            if ($transaction['is_credited_at'] === null) {
                $stmt = $pdo->prepare('UPDATE transactions SET status = :status, is_credited_at = NOW() WHERE id = :id');
                $stmt->bindValue(':status', 'success');
                $stmt->bindValue(':id', $transaction['id']);
                $stmt->execute();
            }

            if ($transaction['source'] === 'direct') {
                $orderClass = new Order();
                $order = $orderClass->findOrderByReference($reference);

                if ($order && $order['status'] === 'pending') {
                    // Update status to 'accepted'
                    $orderClass->updateStatus($order['id'], 'accepted', 'Payment verified manually.');

                    // If the order was set to auto mode, switch it to manual after verification
                    // because manual intervention is now required/happening.
                    if ($order['mode'] === 'auto') {
                        $orderClass->updateOrder($order['id'], ['mode' => 'manual']);
                    }
                    
                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Payment verified and order accepted.', 'order_id' => $order['id']]);
                    exit;
                } elseif ($order) {
                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Payment already verified. Current status: ' . $order['status'], 'order_id' => $order['id']]);
                    exit;
                }
            } else {
                 // Handle user deposit if it wasn't credited
                 if ($transaction['is_credited_at'] === null) {
                    $stmt = $pdo->prepare('UPDATE users SET balance = balance + :amount WHERE id = :id');
                    $stmt->bindValue(':amount', $transaction['principal_amount']);
                    $stmt->bindValue(':id', $transaction['user_id']);
                    $stmt->execute();
                 }
                 $pdo->commit();
                 echo json_encode(['success' => true, 'message' => 'Deposit verified and credited.']);
                 exit;
            }
        } else {
            // Transaction record not found but Paystack says it's success? 
            // This shouldn't happen if initialized from our system, but let's handle it.
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Transaction record not found in database.']);
            exit;
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
} else {
    $msg = $result['message'] ?? 'Transaction not successful on Paystack';
    if (isset($result['data']['status'])) {
        $msg .= " (Status: " . $result['data']['status'] . ")";
    }
    echo json_encode(['success' => false, 'message' => $msg]);
}
