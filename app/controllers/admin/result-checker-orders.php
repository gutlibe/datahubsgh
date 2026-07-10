<?php

checkAdmin();

use App\Classes\ResultChecker;

$rc = new ResultChecker();

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 30;
$offset = ($page - 1) * $limit;
$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$orders = $rc->getOrders($limit, $offset, $status, $search);
$totalCount = $rc->getOrderCount($status, $search);
$totalPages = max(1, ceil($totalCount / $limit));

view('admin/result-checker-orders', [
    'title' => 'Result Checker Orders',
    'layout' => 'admin',
    'orders' => $orders,
    'page' => $page,
    'totalPages' => $totalPages,
    'search' => $search,
    'status' => $status
]);
