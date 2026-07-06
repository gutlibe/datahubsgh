<?php

use App\Classes\Product;
use App\Classes\Configuration;
use App\Classes\Setting;

$config = (new Configuration())->getAll();
$isTelecelEnabled = (bool)($config['enable_telecel_purchase'] ?? true);

if (!$isTelecelEnabled) {
    $products = [];
} else {
    $product = new Product();
    $products = $product->getByNetwork('telecel');
}

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

view('public/networks/telecel', [
    'title' => $appName . ' - Telecel Bundles',
    'products' => $products,
    'layout' => 'guest'
]);
