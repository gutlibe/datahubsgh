<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Product;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$productId = $input['id'] ?? null;
$data = $input['data'] ?? null;

if (!$productId || !$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

try {
    $product = new Product();
    $success = $product->update($productId, $data);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update product.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
