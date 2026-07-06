<?php

use App\Classes\User;

$appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');

// If already logged in, check role and redirect
if (isLoggedIn()) {
    $user = getAuthUser();
    if ($user && $user['role'] === 'admin') {
        redirect($appUrl . '/admin/dashboard');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userClass = new User();
    $authenticatedUser = $userClass->login($email, $password);

    if ($authenticatedUser) {
        if ($authenticatedUser['role'] === 'admin') {
            $_SESSION['user_id'] = $authenticatedUser['id'];
            redirect($appUrl . '/admin/dashboard');
        } else {
            // Only admins allowed for now as per requirements
            view('auth/login', ['layout' => 'guest', 'error' => 'Access denied. Administrator privileges required.']);
        }
    } else {
        view('auth/login', ['layout' => 'guest', 'error' => 'Invalid credentials']);
    }
} else {
    view('auth/login', ['layout' => 'guest']);
}
