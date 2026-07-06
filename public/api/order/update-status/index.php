<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Order;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$orderId = $input['order_id'] ?? null;
$status = $input['status'] ?? null;

if (!$orderId || !$status) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

try {
    $order = new Order();
    $success = $order->updateStatus($orderId, $status, '');

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update order status.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}