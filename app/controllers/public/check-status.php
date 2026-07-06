<?php

use App\Classes\Order;
use App\Classes\Setting;

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

// Handle form submission if reference is provided
$orders = [];
$reference = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reference'])) {
    $reference = trim($_POST['reference']);
    
    // Validate reference format if needed
    if (!empty($reference)) {
        $orderClass = new Order();
        // Find the specific order by reference
        $order = $orderClass->findOrderByReference($reference);
        
        // If order is found, put it in the orders array
        if ($order) {
            $orders = [$order];
        } else {
            $orders = []; // No orders found
        }
    }
} else {
    $orders = [];
}

view('public/check-status', [
    'title' => $appName . ' - Track Your Order',
    'layout' => 'guest',
    'orders' => $orders,
    'reference' => $reference
]);