<?php

require_once __DIR__ . '/../config/bootstrap.php';

$url = $_GET['url'] ?? '';

if (empty($url) || $url === 'index.php') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url = str_replace(BASE_URL, '', $uri);
    $url = str_replace('/public', '', $url);
}

$url = ltrim(rtrim($url, '/'), '/');
if (empty($url)) {
    $url = 'home';
}

$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Define routes and their corresponding controllers
$routes = [
    'home' => 'public/home-new.php',
    'home-old' => 'public/home.php',
    'networks/mtn' => 'public/networks/mtn.php',
    'networks/at' => 'public/networks/at.php',
    'networks/telecel' => 'public/networks/telecel.php',
    'login' => 'auth/login.php',
    'register' => 'auth/register.php',
    'logout' => 'public/logout.php',
    'help' => 'public/help.php',
    'check-status' => 'public/check-status.php',
    'webhook/paystack' => 'webhook/paystack.php',
    'admin/dashboard' => 'admin/dashboard.php',
    'admin/products' => 'admin/products.php',
    'admin/users' => 'admin/users.php',
    'admin/settings' => 'admin/settings.php',
    'admin/configurations' => 'admin/configurations.php',
    'admin/orders' => 'admin/history.php',
    'admin/history' => 'admin/history.php',
    'admin/service-status' => 'admin/service-status.php',
    'admin/providers' => 'admin/providers.php',
    'sudo/migrate' => 'admin/migrate.php',
    'direct-purchase-success' => 'public/direct-purchase-success.php'
];

$controllerFile = __DIR__ . '/../app/controllers/';
if (array_key_exists($url, $routes)) {
    $controllerFile .= $routes[$url];
} else {
    // Redirect unknown routes to home for better experience
    header('Location: ' . rtrim(APP_URL, '/') . '/home');
    exit;
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
} else {
    require_once __DIR__ . '/../app/controllers/public/404.php';
}
