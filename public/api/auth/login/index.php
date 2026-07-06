<?php

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\User;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$user = new User();
$foundUser = $user->findByEmail($data['email']);

if (!$foundUser || !$user->verifyPassword($data['password'], $foundUser['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    exit;
}

// Check if the user is an admin
if ($foundUser['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied. Administrator access required.']);
    exit;
}

$_SESSION['user_id'] = $foundUser['id'];

echo json_encode(['success' => true, 'role' => $foundUser['role']]);
