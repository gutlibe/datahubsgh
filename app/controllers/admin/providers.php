<?php

use App\Classes\Configuration;
use App\Classes\Setting;

if (!isAdmin()) {
    redirect('/login');
}

$title = 'Manage Providers';
$layout = 'admin';

$configuration = new Configuration();
$setting = new Setting();

$configs = $configuration->getAllAsArray();
$hubnetKey = $setting->getSetting('hubnet_key');
$ckgodswayKey = $setting->getSetting('ckgodsway_key'); // Placeholder for second provider

// Route Map from Configurations
$routeMap = [
    'mtn' => $configs['mtn_provider'] ?? 'hubnet',
    'at' => $configs['at_provider'] ?? 'hubnet',
    'telecel' => $configs['telecel_provider'] ?? 'hubnet',
];

view('admin/providers', [
    'title' => $title,
    'layout' => $layout,
    'routeMap' => $routeMap,
    'hubnetKey' => $hubnetKey,
    'ckgodswayKey' => $ckgodswayKey
]);
