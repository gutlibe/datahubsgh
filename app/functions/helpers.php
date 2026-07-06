<?php

function redirect($url)
{
    header("Location: {$url}");
    exit;
}

function format_currency($amount)
{
    return 'GH₵' . number_format($amount, 2);
}

function view($path, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . "/../../views/{$path}.php";

    $setting = new \App\Classes\Setting();
    $appName = $setting->getSetting('app_name') ?? 'Data Portal';

    if (isset($layout)) {
        // Automatically pass APP_URL and appName to layouts
        if (!isset($data['app_url'])) {
            $data['app_url'] = $_ENV['APP_URL'] ?? ''; 
        }
        if (!isset($data['appName'])) {
            $data['appName'] = $appName;
        }
        extract($data); 

        $content = $viewPath;
        require __DIR__ . "/../../views/layouts/{$layout}.php";
    } else {
        require $viewPath;
    }
}

/**
 * Verifies a recipient number before a purchase is attempted, using the CKGodsway
 * provider's /verify endpoint. Controlled by the "verify_phone_before_payment" config.
 * Only actually runs when the network's currently active provider is CKGodsway -
 * for networks routed to Hubnet this is a no-op (returns true) since Hubnet's API
 * has no verification endpoint.
 *
 * @param array $configs Result of Configuration::getAll()/getAllAsArray().
 * @param string $network The mobile network (e.g., MTN).
 * @param string $msisdn The recipient phone number.
 * @return bool True if verification passed (or was skipped/not required).
 */
function isRecipientNumberVerified(array $configs, string $network, string $msisdn): bool
{
    if (($configs['verify_phone_before_payment'] ?? '0') != '1') {
        return true;
    }

    $networkId = strtolower($network);
    $providerSlug = $configs["{$networkId}_provider"] ?? 'hubnet';

    if ($providerSlug !== 'ckgodsway') {
        return true;
    }

    $ck = new \App\Classes\CKGodswayService();
    $result = $ck->verifyNumber($network, $msisdn);

    return (bool)($result['success'] ?? false);
}

function asset($path)
{
    $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    $originalPath = '/' . ltrim($path, '/');
    
    // Check environment
    $env = $_ENV['APP_ENV'] ?? 'production';
    
    // If local, serve original file directly from public folder
    if ($env === 'local') {
        return $appUrl . $originalPath;
    }
    
    // In production, use manifest
    static $manifest = null;
    if ($manifest === null) {
        $manifestPath = __DIR__ . '/../../public/mix-manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } else {
            $manifest = [];
        }
    }
    
    if (isset($manifest[$originalPath])) {
        return $appUrl . $manifest[$originalPath];
    }
    
    return $appUrl . $originalPath;
}