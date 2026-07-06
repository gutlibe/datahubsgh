<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../../config/bootstrap.php';

use App\Classes\Database;

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data provided']);
    exit;
}

$db = Database::getInstance()->getConnection();

try {
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    
    foreach ($data as $key => $value) {
        // Basic validation or filtering can be done here
        if (in_array($key, ['hubnet_key', 'ckgodsway_key'])) {
            $stmt->execute([$key, $value, $value]);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Keys updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
