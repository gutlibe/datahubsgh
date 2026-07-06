<?php
// router.php for PHP built-in server
if (php_sapi_name() == 'cli-server') {
    $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $publicDir = __DIR__;
    $projectDir = dirname(__DIR__);

    // Handle /api requests
    if (strpos($uri, '/api/') === 0) {
        // Map /api/... to current_dir/api/...
        $apiPath = $publicDir . $uri;
        
        // If it's a directory, look for index.php
        if (is_dir($apiPath)) {
            $apiPath = rtrim($apiPath, '/') . '/index.php';
        }

        if (file_exists($apiPath)) {
            require $apiPath;
            return true; // Stop processing, we handled it
        }
    }

    // Default static file handling for 'public' dir
    $file = $publicDir . $uri;
    // If the file exists and is not a directory, serve it directly
    if (file_exists($file) && !is_dir($file)) {
        return false;
    }

    // Otherwise, rewrite to index.php?url=$path
    // We need to populate $_GET['url'] as index.php expects it
    $_GET['url'] = ltrim($uri, '/');
    
    // Include the main index file
    require __DIR__ . '/index.php';
}
