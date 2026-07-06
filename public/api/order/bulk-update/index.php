<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Database;
use App\Classes\Order;

// Check admin authentication
if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderIds = $data['order_ids'] ?? [];
$status = $data['status'] ?? '';

if (empty($orderIds) || !is_array($orderIds)) {
    echo json_encode(['success' => false, 'message' => 'No orders selected']);
    exit;
}

$validStatuses = ['pending', 'accepted', 'processing', 'delivered', 'failed'];
if (!in_array($status, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

$orderClass = new Order();
$successCount = 0;
$failCount = 0;

foreach ($orderIds as $id) {
    if ($orderClass->updateStatus((int)$id, $status, 'Bulk update by admin')) {
        $successCount++;
    } else {
        $failCount++;
    }
}

echo json_encode([
    'success' => true, 
    'message' => "Updated $successCount orders successfully." . ($failCount > 0 ? " ($failCount failed)" : "")
]);
