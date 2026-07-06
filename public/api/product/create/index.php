<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Product;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$data = $input['data'] ?? null;

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

try {
    $product = new Product();
    $productId = $product->create($data);

    if ($productId) {
        echo json_encode(['success' => true, 'message' => 'Product created successfully.', 'product_id' => $productId]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create product.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
