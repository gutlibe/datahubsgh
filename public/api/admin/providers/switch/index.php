<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../../config/bootstrap.php';

use App\Classes\Database;

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$network = $data['network'] ?? null;
$providerSlug = $data['provider_slug'] ?? null;

if (!$network || !$providerSlug) {
    echo json_encode(['success' => false, 'message' => 'Network and Provider Slug are required']);
    exit;
}

$db = Database::getInstance()->getConnection();

try {
    // Update configurations: {network}_provider
    $configKey = strtolower($network) . '_provider';
    
    $stmt = $db->prepare("
        INSERT INTO configurations (name, value) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE value = ?
    ");
    $stmt->execute([$configKey, $providerSlug, $providerSlug]);

    echo json_encode(['success' => true, 'message' => "Routing updated for {$network} to {$providerSlug}"]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
