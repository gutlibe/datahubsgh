<?php

checkAdmin();

use App\Classes\User;
use App\Classes\Order;

$user = new User();
$order = new Order();

$totalUsers = $user->countAll();
$totalSales = $order->getTotalSales();

$today = date('Y-m-d');
$this_week = date('Y-m-d', strtotime('monday this week'));
$this_month = date('Y-m-01');

$todaySales = $order->getSalesByPeriod($today);
$weekSales = $order->getSalesByPeriod($this_week);
$monthSales = $order->getSalesByPeriod($this_month);

$networkStats = $order->getNetworkStats();
$statusStats = $order->getStatusStats();
$recentOrders = $order->getAllOrders(5);

$stats = [
    'total_users' => $totalUsers,
    'total_sales' => $totalSales,
    'today_sales' => $todaySales,
    'this_week_sales' => $weekSales,
    'this_month_sales' => $monthSales,
    'network_stats' => $networkStats,
    'status_stats' => $statusStats
];

view('admin/dashboard', [
    'title' => 'Admin Dashboard', 
    'stats' => $stats, 
    'recent_orders' => $recentOrders,
    'layout' => 'admin'
]);
