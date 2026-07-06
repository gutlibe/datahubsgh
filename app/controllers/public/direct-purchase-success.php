<?php

use App\Classes\Order;
use App\Classes\Setting;

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

$reference = $_GET['ref'] ?? null;

if (!$reference) {
    redirect(rtrim($_ENV['APP_URL'], '/') . '/home');
}

$order = new Order();
$orderDetails = $order->findOrderByReference($reference);

if (!$orderDetails) {
    redirect(rtrim($_ENV['APP_URL'], '/') . '/home');
}

view('public/direct-purchase-success', [
    'title' => $appName . ' - Purchase Successful',
    'layout' => 'guest',
    'order' => $orderDetails,
    'reference' => $reference
]);