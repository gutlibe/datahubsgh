<?php

use App\Classes\Product;
use App\Classes\Configuration;
use App\Classes\Setting;

$config = (new Configuration())->getAll();
$isAtEnabled = (bool)($config['enable_at_purchase'] ?? true);

if (!$isAtEnabled) {
    $products = [];
} else {
    $product = new Product();
    $products = $product->getByNetwork('at');
}

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

view('public/networks/at', [
    'title' => $appName . ' - AirtelTigo Bundles',
    'products' => $products,
    'layout' => 'guest'
]);
