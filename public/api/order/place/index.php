<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\User;
use App\Classes\Product;
use App\Classes\Order;
use App\Classes\Database;

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;
$reference = $data['reference'] ?? null;
$msisdn = $data['msisdn'] ?? null;

if (!$productId || !$reference || !$msisdn) {
    echo json_encode(['success' => false, 'message' => 'Product ID and reference are required.']);
    exit;
}

$db = Database::getInstance()->getConnection();

try {
    $user = getAuthUser();
    $product = (new Product())->findById($productId);

    if (!$product || (isset($product['is_available']) && !$product['is_available'])) {
        throw new Exception('Product is currently unavailable.');
    }

    $configs = (new \App\Classes\Configuration())->getAll();
    $network = strtolower($product['network']);
    $isNetworkEnabled = (bool)($configs["enable_{$network}_purchase"] ?? true);

    if (!$isNetworkEnabled) {
        throw new Exception(strtoupper($network) . ' purchases are temporarily unavailable.');
    }

    if (!isRecipientNumberVerified($configs, $product['network'], $msisdn)) {
        throw new Exception("We're unable to process this number at the moment. Please try again later or use a different number.");
    }

    $db->beginTransaction();

    // Re-check user from DB within transaction for locking
    $userClass = new User();
    $user = $userClass->findByIdForUpdate($user['id']);

    // 1. Balance Check
    if ($user['balance'] < $product['customer_price']) {
        throw new Exception('Insufficient balance.');
    }

    // 2. Create Order
    $order = new Order();
    $orderData = [
        'user_id' => $user['id'],
        'product_id' => $product['id'],
        'reference' => $reference,
        'msisdn' => $msisdn,
        'amount' => $product['customer_price'],
        'network' => $product['network'],
        'volume' => $product['volume'],
        'product_name' => $product['name'],
        'status' => 'pending',
        'mode' => 'manual',
        'source' => 'web'
    ];

    $orderId = $order->create($orderData);
    if (!$orderId) {
        throw new Exception('Failed to create order.');
    }

    // 3. Deduct Balance
    $newBalance = $user['balance'] - $product['customer_price'];
    if (!$userClass->updateBalance($user['id'], $newBalance)) {
        throw new Exception('Failed to update balance.');
    }

    // 4. Update Order Status to 'accepted'
    // $order->updateStatus($orderId, 'accepted', 'Purchase accepted. Awaiting delivery.');
    $db->commit();

    // 5. Start Processing
    $processingService = new \App\Classes\ProcessingService();
    $processingService->handleOrderProcessing($orderId);


    echo json_encode(['success' => true, 'message' => 'Purchase successful! Your order is being processed.']);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
