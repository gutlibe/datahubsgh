<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Classes\Env;

Env::load(__DIR__ . '/../.env');

// Define APP_URL from environment
define('APP_URL', $_ENV['APP_URL'] ?? '');

// Define a base URL for the application (path only)
$appUrlPath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
define('BASE_URL', rtrim($appUrlPath, '/'));

session_start();

require_once __DIR__ . '/../app/functions/helpers.php';
require_once __DIR__ . '/../app/functions/auth.php';
