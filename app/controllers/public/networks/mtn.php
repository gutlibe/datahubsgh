<?php

use App\Classes\Product;
use App\Classes\Configuration;
use App\Classes\Setting;

$config = (new Configuration())->getAll();
$isMtnEnabled = (bool)($config['enable_mtn_purchase'] ?? true);

if (!$isMtnEnabled) {
    $products = [];
} else {
    $product = new Product();
    $products = $product->getByNetwork('mtn');
}

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

view('public/networks/mtn', [
    'title' => $appName . ' - MTN Bundles',
    'products' => $products,
    'layout' => 'guest'
]);
