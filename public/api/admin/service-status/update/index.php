<?php

require_once __DIR__ . '/../../../../../config/bootstrap.php';

checkAdmin();

use App\Classes\Setting;

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['message'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$setting = new Setting();
$setting->setSetting('service_status_message', $data['message']);

echo json_encode(['success' => true, 'message' => 'Service status updated successfully.']);
