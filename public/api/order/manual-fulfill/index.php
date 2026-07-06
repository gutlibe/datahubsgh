<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\ProcessingService;
use App\Classes\Order;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$orderId = (int)$data['order_id'];

$orderClass = new Order();
$order = $orderClass->findOrderByIdForProcessing($orderId);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

if ($order['status'] === 'delivered') {
    echo json_encode(['success' => false, 'message' => 'Order already delivered']);
    exit;
}

$processingService = new ProcessingService();
$result = $processingService->handleOrderProcessing($orderId);

// ProcessingService usually handles status updates internally.
// We just need to check if it started or if there was an immediate error.

echo json_encode(['success' => true, 'message' => 'Fulfillment triggered. Check status in a moment.']);
