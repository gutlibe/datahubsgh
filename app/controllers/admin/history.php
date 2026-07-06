<?php

checkAdmin();

use App\Classes\Order;

$orderClass = new Order();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;
$status = $_GET['status'] ?? '';
$provider = $_GET['provider'] ?? '';
$network = $_GET['network'] ?? '';
$mode = $_GET['mode'] ?? '';
$searchTerm = $_GET['search'] ?? '';

$orders = $orderClass->getAllOrders($limit, $offset, $status, $searchTerm, $provider, $network, $mode);
$totalOrders = $orderClass->getOrderCount($status, $searchTerm, $provider, $network, $mode);
$totalPages = ceil($totalOrders / $limit);

// Define all possible statuses
$statuses = ['accepted', 'processing', 'delivered', 'failed'];
// Define providers
$providers = ['hubnet', 'ckgodsway'];
// Define networks
$networks = ['mtn', 'at', 'telecel'];
// Define modes
$modes = ['auto', 'manual'];

view("admin/history", [
    "orders" => $orders,
    "totalPages" => $totalPages,
    "currentPage" => $page,
    "status" => $status,
    "provider" => $provider,
    "network" => $network,
    "mode" => $mode,
    "searchTerm" => $searchTerm,
    "statuses" => $statuses,
    "providers" => $providers,
    "networks" => $networks,
    "modes" => $modes,
    "layout" => "admin",
    "page_title" => "Order History"
]);