<?php

use App\Classes\User;

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Fetches the authenticated user directly from the database.
 * This ensures the role and other details are always current.
 */
function getAuthUser()
{
    static $authUser = null;

    if (!isLoggedIn()) {
        return null;
    }

    if ($authUser === null) {
        $userId = $_SESSION['user_id'];
        $user = new User();
        $authUser = $user->findById($userId);
    }

    return $authUser;
}

/**
 * Checks if the current user has admin privileges by querying the database.
 */
function checkAdmin()
{
    $user = getAuthUser();
    
    // If user doesn't exist in DB or doesn't have the admin role, deny access.
    if (!$user || $user['role'] !== 'admin') {
        // Clear session if user was deleted or demoted
        if (!$user) {
            session_destroy();
        }
        
        header('Location: ' . rtrim(APP_URL, '/') . '/login');
        exit;
    }
}

/**
 * Returns true if the current user is an admin based on the database role.
 */
function isAdmin()
{
    $user = getAuthUser();
    return $user && $user['role'] === 'admin';
}