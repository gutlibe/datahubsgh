<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Product;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$productIds = $input['ids'] ?? null;
$action = $input['action'] ?? null;

if (!$productIds || !$action) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

try {
    $product = new Product();
    $success = false;

    if ($action === 'enable') {
        $success = $product->bulkUpdateAvailability($productIds, true);
    } elseif ($action === 'disable') {
        $success = $product->bulkUpdateAvailability($productIds, false);
    }

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Products updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update products.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
