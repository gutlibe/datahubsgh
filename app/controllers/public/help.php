<?php

$setting = new \App\Classes\Setting();
$settings = $setting->getAllSettings();

view('public/help', [
    'title' => 'Help & Support',
    'layout' => 'guest',
    'settings' => $settings
]);