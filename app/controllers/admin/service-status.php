<?php

checkAdmin();

use App\Classes\Setting;

$setting = new Setting();
$service_status_message = $setting->getSetting('service_status_message');

view('admin/service-status', ['title' => 'Service Status', 'service_status_message' => $service_status_message, 'layout' => 'admin']);
