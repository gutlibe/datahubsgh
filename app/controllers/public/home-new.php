 <?php

use App\Classes\Product;
use App\Classes\Setting;
use App\Classes\Configuration;

$config = (new Configuration())->getAll();

$productModel = new Product();
$mtnProducts = ($config['enable_mtn_purchase'] ?? '1') === '1' ? $productModel->getByNetwork('mtn') : [];
$atProducts = ($config['enable_at_purchase'] ?? '1') === '1' ? $productModel->getByNetwork('at') : [];
$telecelProducts = ($config['enable_telecel_purchase'] ?? '1') === '1' ? $productModel->getByNetwork('telecel') : [];

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';
$service_status_message = $setting->getSetting('service_status_message');

view('public/home-new', [
    'title' => $appName . ' - Buy Data Bundles (All Networks)',
    'layout' => 'guest',
    'mtnProducts' => $mtnProducts,
    'atProducts' => $atProducts,
    'telecelProducts' => $telecelProducts,
    'service_status_message' => $service_status_message,
    'configs' => $config
]);