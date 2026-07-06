<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Product;
use App\Classes\Transaction;
use App\Classes\Setting;
use App\Classes\Order;
use App\Classes\Configuration;

header('Content-Type: application/json');

// Check if direct purchase is enabled
$configClass = new Configuration();
$allConfigs = $configClass->getAll();
$isDirectPurchaseEnabled = $allConfigs['enable_direct_purchase'] ?? '0';

if ($isDirectPurchaseEnabled === '0') {
    echo json_encode(['success' => false, 'message' => 'Direct purchases are temporarily unavailable. Please try again later.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['product_id']) || !isset($data['msisdn'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$productId = filter_var($data['product_id'], FILTER_VALIDATE_INT);
$msisdn = htmlspecialchars($data['msisdn']);

if (!$productId || !$msisdn) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or phone number']);
    exit;
}

$productClass = new Product();
$product = $productClass->findById($productId);

if (!$product || (isset($product['is_available']) && !$product['is_available'])) {
    echo json_encode(['success' => false, 'message' => 'Product is currently unavailable']);
    exit;
}

$network = strtolower($product['network']);
$isNetworkEnabled = (bool)($allConfigs["enable_{$network}_purchase"] ?? true);

if (!$isNetworkEnabled) {
    echo json_encode(['success' => false, 'message' => strtoupper($network) . ' purchases are temporarily unavailable.']);
    exit;
}

if (!isRecipientNumberVerified($allConfigs, $product['network'], $msisdn)) {
    echo json_encode(['success' => false, 'message' => "We're unable to process this number at the moment. Please try again later or use a different number."]);
    exit;
}

$transactionClass = new Transaction();
$reference = strtoupper(bin2hex(random_bytes(8)));
$amount = $product['customer_price'];

$transactionId = $transactionClass->create([
    'user_id' => null,
    'reference' => $reference,
    'amount' => $amount,
    'status' => 'pending',
    'source' => 'direct'
]);

if ($transactionId === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to create transaction. Please try again later.']);
    exit;
}

// Determine processing mode
$configuration = new \App\Classes\Configuration();
$configs = $configuration->getAllAsArray();
$isGlobalAutoProcessingEnabled = (bool)($configs['auto_processing'] ?? false);
$network = strtolower($product['network']);
$isNetworkAutoProcessingEnabled = (bool)($configs["auto_{$network}_processing"] ?? false); // Assuming config key like 'auto_mtn_processing'

$mode = null; // Default to null, meaning undecided/not auto-processed yet

if ($isGlobalAutoProcessingEnabled && $isNetworkAutoProcessingEnabled) {
    $mode = 'auto';
}

// Create only an order record (unified approach)
$orderClass = new Order();
$orderId = $orderClass->create([
    'user_id' => null,
    'product_id' => $productId,
    'reference' => $reference,
    'msisdn' => $msisdn,
    'amount' => $amount,
    'network' => $product['network'],
    'volume' => $product['volume'],
    'product_name' => $product['name'],
    'status' => 'pending',
    'mode' => $mode,
    'source' => 'direct'
]);

if ($orderId === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to create order. Please try again later.']);
    exit;
}

// Paystack Integration
$setting = new Setting();
$paystackSecretKey = $setting->getSetting('paystack_secret_key');
$callbackUrl = rtrim($_ENV['APP_URL'], '/') . '/direct-purchase-success?ref=' . $reference;

$postData = [
    'email' => $msisdn . "@datahubsgh.com",
    'amount' => (int)($amount * 100),
    'reference' => $reference,
    'callback_url' => $callbackUrl,
    'metadata' => [
        'product_name' => $product['name'],
        'network' => $product['network'],
        'msisdn' => $msisdn,
        'customer_price' => $amount,
        'order_source' => 'direct'
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transaction/initialize');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $paystackSecretKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['success' => false, 'message' => 'Error initializing payment']);
    exit;
}

$result = json_decode($response, true);

if ($result['status']) {
    echo json_encode(['success' => true, 'authorization_url' => $result['data']['authorization_url']]);
} else {
    echo json_encode(['success' => false, 'message' => $result['message']]);
}
