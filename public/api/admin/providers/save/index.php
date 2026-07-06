<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../../config/bootstrap.php';

use App\Classes\Database;

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$name = $data['name'] ?? null;
$slug = $data['slug'] ?? null;
$config = $data['config'] ?? null; // JSON string expected
$isActive = $data['is_active'] ?? 1;

if (!$name || !$slug || !$config) {
    echo json_encode(['success' => false, 'message' => 'Name, Slug and Config are required']);
    exit;
}

// Validate Config JSON
if (!json_decode($config)) {
     echo json_encode(['success' => false, 'message' => 'Invalid JSON Config']);
     exit;
}

$db = Database::getInstance()->getConnection();

try {
    if ($id) {
        $stmt = $db->prepare("UPDATE providers SET name=?, slug=?, config=?, is_active=? WHERE id=?");
        $stmt->execute([$name, $slug, $config, $isActive, $id]);
        $message = 'Provider updated successfully';
    } else {
        $stmt = $db->prepare("INSERT INTO providers (name, slug, config, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $config, $isActive]);
        $message = 'Provider created successfully';
    }

    echo json_encode(['success' => true, 'message' => $message]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
