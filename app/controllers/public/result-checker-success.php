<?php

use App\Classes\ResultChecker;
use App\Classes\Setting;

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

$reference = $_GET['ref'] ?? null;

if (!$reference) {
    redirect(rtrim($_ENV['APP_URL'], '/') . '/home');
}

$rc = new ResultChecker();
$order = $rc->findOrderByReference($reference);

if (!$order) {
    redirect(rtrim($_ENV['APP_URL'], '/') . '/home');
}

view('public/result-checker-success', [
    'title' => $appName . ' - Purchase Successful',
    'layout' => 'guest',
    'order' => $order,
    'reference' => $reference
]);
