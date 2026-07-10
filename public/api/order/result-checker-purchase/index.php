<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Transaction;
use App\Classes\Setting;
use App\Classes\Configuration;
use App\Classes\ResultChecker;

header('Content-Type: application/json');

$configClass = new Configuration();
$allConfigs = $configClass->getAll();

if (($allConfigs['enable_result_checker'] ?? '0') === '0') {
    echo json_encode(['success' => false, 'message' => 'Result checker purchases are temporarily unavailable. Please try again later.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['checker_type']) || !isset($data['msisdn'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$checkerType = strtoupper(trim($data['checker_type']));
$msisdn = htmlspecialchars($data['msisdn']);
$quantity = filter_var($data['quantity'] ?? 1, FILTER_VALIDATE_INT);

if (empty($checkerType) || empty($msisdn)) {
    echo json_encode(['success' => false, 'message' => 'Invalid checker type or phone number']);
    exit;
}

if (!preg_match('/^0[0-9]{9}$/', $msisdn)) {
    echo json_encode(['success' => false, 'message' => 'Phone number must be exactly 10 digits starting with 0']);
    exit;
}

if (!$quantity || $quantity < 1 || $quantity > 30) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be between 1 and 30']);
    exit;
}

$rc = new ResultChecker();
$type = $rc->findType($checkerType);

if (!$type || (int)$type['enabled'] !== 1) {
    echo json_encode(['success' => false, 'message' => 'The selected result checker type is not currently available']);
    exit;
}

$unitPrice = $rc->resolveUnitPrice($checkerType, $quantity);
if ($unitPrice === null) {
    echo json_encode(['success' => false, 'message' => 'Pricing is not configured for this result checker type']);
    exit;
}

$amount = round($unitPrice * $quantity, 2);

$transactionClass = new Transaction();
$reference = 'RC-' . strtoupper(bin2hex(random_bytes(8)));

$transactionId = $transactionClass->create([
    'user_id' => null,
    'reference' => $reference,
    'amount' => $amount,
    'status' => 'pending',
    'source' => 'result_checker'
]);

if ($transactionId === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to create transaction. Please try again later.']);
    exit;
}

$orderId = $rc->createOrder([
    'user_id' => null,
    'checker_type' => $checkerType,
    'display_name' => $type['display_name'],
    'quantity' => $quantity,
    'msisdn' => $msisdn,
    'unit_price' => $unitPrice,
    'amount' => $amount,
    'reference' => $reference,
    'status' => 'pending'
]);

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Failed to create order. Please try again later.']);
    exit;
}

// Paystack Integration
$setting = new Setting();
$paystackSecretKey = $setting->getSetting('paystack_secret_key');
$callbackUrl = rtrim($_ENV['APP_URL'], '/') . '/result-checker-success?ref=' . $reference;

$postData = [
    'email' => $msisdn . "@datacitygh.com",
    'amount' => (int)($amount * 100),
    'reference' => $reference,
    'callback_url' => $callbackUrl,
    'metadata' => [
        'checker_type' => $type['display_name'],
        'quantity' => $quantity,
        'msisdn' => $msisdn,
        'order_source' => 'result_checker'
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
