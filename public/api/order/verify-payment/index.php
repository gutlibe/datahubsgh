<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Database;
use App\Classes\Order;
use App\Classes\Transaction;
use App\Classes\Setting;
use App\Classes\ProcessingService;

// Security check: Only allow in local environment or if explicitly enabled
$env = $_ENV['APP_ENV'] ?? 'production';

if ($env !== 'local') {
    echo json_encode(['success' => false, 'message' => 'Manual verification is only available in local environment']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$reference = $data['reference'] ?? null;

if (!$reference) {
    echo json_encode(['success' => false, 'message' => 'Reference is required']);
    exit;
}

$transactionClass = new Transaction();
$transaction = $transactionClass->findByReference($reference);

if (!$transaction) {
    echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    exit;
}

if ($transaction['status'] === 'success') {
    echo json_encode(['success' => true, 'message' => 'Transaction already successful', 'status' => 'success']);
    exit;
}

// Verify with Paystack
$setting = new Setting();
$paystackSecretKey = $setting->getSetting('paystack_secret_key');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transaction/verify/' . $reference);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $paystackSecretKey]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for potential SSL issues on local/Termux
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result && $result['status'] && $result['data']['status'] === 'success') {
    $db = Database::getInstance()->getConnection();
    try {
        $db->beginTransaction();

        // 1. Lock the transaction row
        $stmt = $db->prepare('SELECT id, status FROM transactions WHERE id = ? FOR UPDATE');
        $stmt->execute([$transaction['id']]);
        $lockedTransaction = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Re-check status inside the lock
        if (!$lockedTransaction || $lockedTransaction['status'] === 'success') {
            $db->rollBack();
            echo json_encode(['success' => true, 'message' => 'Transaction already successful (Race condition handled)', 'status' => 'success']);
            exit;
        }

        // Update Transaction
        $stmt = $db->prepare('UPDATE transactions SET status = "success", is_credited_at = NOW() WHERE id = ?');
        $stmt->execute([$transaction['id']]);

        // Update Order and Process
        $orderClass = new Order();
        $order = $orderClass->findOrderByReference($reference);
        
        if ($order) {
            $orderClass->updateStatus($order['id'], 'accepted', 'Payment verified manually (Local Dev).');
            
            // Trigger processing
            $processingService = new ProcessingService();
            $processingService->handleOrderProcessing($order['id']);
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Payment verified successfully', 'status' => 'success']);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error processing order: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Payment verification failed', 'status' => $result['data']['status'] ?? 'failed']);
}
