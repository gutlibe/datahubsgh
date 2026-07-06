<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Order;

$reference = $_GET['reference'] ?? null;

if (!$reference) {
    echo json_encode(['success' => false, 'message' => 'Reference is required']);
    exit;
}

$orderModel = new Order();
$orders = $orderModel->getByReference($reference);

echo json_encode([
    'success' => true,
    'orders' => $orders
]);