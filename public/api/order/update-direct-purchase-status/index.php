<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\DirectPurchase;
use App\Classes\User;

header('Content-Type: application/json');

checkAdmin();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$id = filter_var($data['id'], FILTER_VALIDATE_INT);
$status = htmlspecialchars($data['status']);

if (!$id || !in_array($status, ['pending', 'accepted', 'processing', 'delivered', 'failed'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID or status']);
    exit;
}

$directPurchaseClass = new DirectPurchase();
$success = $directPurchaseClass->updateStatus($id, $status);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}
