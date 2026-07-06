<?php

require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\User;

header('Content-Type: application/json');

try {
    $raw_input = file_get_contents('php://input');
    $data = json_decode($raw_input, true);

    if (json_last_error() !== JSON_ERROR_NONE || !$data || !isset($data['first_name']) || !isset($data['last_name']) || !isset($data['phone_number']) || !isset($data['email']) || !isset($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input. All fields are required.']);
        exit;
    }

    $user = new User();

    if ($user->findByEmail($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    $userId = $user->create($data['first_name'], $data['last_name'], $data['phone_number'], $data['email'], $data['password'], 'customer');

    if ($userId) {
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create user.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'A database error occurred.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}