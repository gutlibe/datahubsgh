<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\User;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

checkAdmin();

$userId = $_POST['userId'] ?? null;
$balance = $_POST['balance'] ?? null;
$role = $_POST['role'] ?? null;

if (!$userId || $balance === null || !$role) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$user = new User();
$success = $user->updateUserDetails($userId, $balance, $role);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
}
