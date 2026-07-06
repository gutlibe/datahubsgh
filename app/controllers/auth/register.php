<?php

if (isLoggedIn()) {
    $user = getAuthUser();
    if ($user && $user['role'] === 'admin') {
        redirect(rtrim($_ENV['APP_URL'], '/') . '/admin/dashboard');
    }
}

view('auth/register', ['layout' => 'guest']);