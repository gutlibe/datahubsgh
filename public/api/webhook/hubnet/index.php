<?php
// Determine the correct path to bootstrap.php
$bootstrapPath = __DIR__ . '/../../../../config/bootstrap.php';
if (!file_exists($bootstrapPath)) {
    // Try alternative path that might be used in production
    $bootstrapPath = __DIR__ . '/../../../../../config/bootstrap.php';
    if (!file_exists($bootstrapPath)) {
        die('Could not find config/bootstrap.php');
    }
}

require_once $bootstrapPath;

use App\Classes\Order;
use App\Classes\DirectPurchase;
use App\Classes\TelegramNotifier;
use App\Classes\Transaction;
use App\Classes\Product;
use App\Classes\Database;

$payload = json_decode(file_get_contents('php://input'), true);

if (!$payload || !isset($payload['data']['reference'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid payload.']);
    exit;
}

$reference = $payload['data']['reference'];
$hubnetStatus = strtolower($payload['event'] ?? '');
$hubnetMessage = $payload['message'] ?? 'Status updated via Hubnet webhook.';

$newStatus = '';
switch ($hubnetStatus) {
    case 'transfer.processing':
        $newStatus = 'processing';
        break;
    case 'transfer.delivered':
        $newStatus = 'delivered';
        break;
    case 'transfer.failed':
        $newStatus = 'failed';
        break;
    default:
        http_response_code(200); // Acknowledge receipt of other statuses without processing
        exit;
}

// Initialize database connection for transaction
$db = Database::getInstance()->getConnection();

try {
    $db->beginTransaction();

    // Check if it's a direct purchase or a regular order
    if (strpos($reference, 'DP-') === 0) {
        $directPurchaseClass = new DirectPurchase();
        $directPurchase = $directPurchaseClass->findByReference($reference);

        // Update the direct_purchases table
        if ($directPurchase && $newStatus !== $directPurchase['status']) {
            $directPurchaseClass->updateStatus($directPurchase['id'], $newStatus);

            $transactionClass = new Transaction();
            $transaction = $transactionClass->findById($directPurchase['transaction_id']);

            $productClass = new Product();
            $product = $productClass->findById($directPurchase['product_id']);

            $orderData = [
                'email' => $transaction['email'] ?? 'N/A',
                'network' => $product['network'],
                'reference' => $reference,
                'volume' => $product['volume'],
                'receiver' => $directPurchase['msisdn'],
                'amount' => $transaction['amount']
            ];

            $telegramNotifier = new TelegramNotifier();
            switch ($newStatus) {
                case 'processing':
                    $telegramNotifier->sendProcessingNotification($orderData);
                    break;
                case 'delivered':
                    $telegramNotifier->sendDeliveredNotification($orderData);
                    break;
                case 'failed':
                    $telegramNotifier->sendFailedNotification($orderData);
                    break;
            }
        }
        
        // Also update the corresponding order in the orders table if it exists
        $orderClass = new Order();
        $order = $orderClass->findOrderByReference($reference);
        if ($order && $newStatus !== $order['status']) {
            $orderClass->updateStatus($order['id'], $newStatus, $hubnetMessage);
        }
    } else {
        // Handle all orders regardless of source (direct or user)
        $orderClass = new Order();
        $order = $orderClass->findOrderByReference($reference);

        if ($order && $newStatus !== $order['status']) {
            $orderClass->updateStatus($order['id'], $newStatus, $hubnetMessage);

            // Send notification for all orders
            $productClass = new Product();
            $product = $productClass->findById($order['product_id']);

            $source = $order['source'] ?? 'User Order'; // Default to User Order if source not set
            if ($source === 'direct') {
                $source = 'Direct Purchase';
            }
            
            // Get user information if available
            $userEmail = 'N/A';
            if ($order['user_id']) {
                $userClass = new \App\Classes\User();
                $user = $userClass->findById($order['user_id']);
                $userEmail = $user['email'] ?? 'N/A';
            }

            $orderData = [
                'email' => $userEmail,
                'network' => $order['network'],
                'reference' => $reference,
                'volume' => $order['volume'],
                'receiver' => $order['msisdn'],
                'source' => $source,
                'amount' => $order['amount']
            ];

            $telegramNotifier = new TelegramNotifier();
            switch ($newStatus) {
                case 'processing':
                    $telegramNotifier->sendProcessingNotification($orderData);
                    break;
                case 'delivered':
                    $telegramNotifier->sendDeliveredNotification($orderData);
                    break;
                case 'failed':
                    $telegramNotifier->sendFailedNotification($orderData);
                    break;
            }
        }
    }

    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    error_log('HubNet webhook error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}

http_response_code(200);
echo json_encode(['success' => true]);
