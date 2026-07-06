<?php
require_once __DIR__ . '/../../../../config/bootstrap.php';

use App\Classes\Order;

header('Content-Type: application/json');

$network = $_GET['network'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$searchType = $_GET['search_type'] ?? null;
$searchQuery = $_GET['search_query'] ?? null;
$mode = $_GET['mode'] ?? 'auto';

if (!$network) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$orderInstance = new Order();
$orders = $orderInstance->findAll($mode);

if ($searchType && $searchQuery) {
    $orders = array_filter($orders, function($order) use ($searchType, $searchQuery) {
        if ($searchType === 'id') {
            return $order['id'] == $searchQuery;
        } elseif ($searchType === 'msisdn') {
            return strpos($order['msisdn'], $searchQuery) !== false;
        } elseif ($searchType === 'reference') {
            return strpos($order['reference'], $searchQuery) !== false;
        }
        return false;
    });
}

function generate_orders_table($orders, $network, $page = 1, $perPage = 10) {
    $filteredOrders = array_filter($orders, function($order) use ($network) {
        return strtolower($order['network']) === strtolower($network);
    });

    $total = count($filteredOrders);
    $pages = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    $paginatedOrders = array_slice($filteredOrders, $offset, $perPage);

    if (empty($paginatedOrders)) {
        return '<p>No orders found for ' . htmlspecialchars($network) . '.</p>';
    }

    $table = '<div class="overflow-x-auto"><table class="min-w-full bg-white">';
    $table .= '<thead><tr class="w-full h-16 border-gray-300 border-b py-8">';
    $table .= '<th class="text-left pl-8">Order ID</th>';
    $table .= '<th class="text-left pl-8">User ID</th>';
    $table .= '<th class="text-left pl-8">Product</th>';
    $table .= '<th class="text-left pl-8">Number</th>';
    $table .= '<th class="text-left pl-8">Status</th>';
    $table .= '<th class="text-left pl-8">Date</th>';
    $table .= '<th class="text-left pl-8">Actions</th>';
    $table .= '</tr></thead><tbody>';

    foreach ($paginatedOrders as $order) {
        $table .= '<tr class="h-14 border-gray-300 border-b">';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['id']) . '</td>';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['user_id']) . '</td>';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['product_name']) . '</td>';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['msisdn'] ?? '') . '</td>';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['status']) . '</td>';
        $table .= '<td class="pl-8">' . htmlspecialchars($order['created_at']) . '</td>';
        $statuses = ['pending', 'accepted', 'processing', 'delivered', 'failed'];
        $options = '';
        foreach ($statuses as $status) {
            $selected = (strtolower($order['status']) === $status) ? 'selected' : '';
            $options .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
        }
        $table .= '<td class="pl-8">';
        $table .= '<select class="border rounded px-2 py-1 change-status-dropdown" data-order-id="' . htmlspecialchars($order['id']) . '">';
        $table .= $options;
        $table .= '</select>';
        $table .= '</td>';
        $table .= '</tr>';
    }

    $table .= '</tbody></table></div>';

    $table .= '<div class="flex justify-center mt-4 pagination-links" data-network="' . htmlspecialchars($network) . '">';
    for ($i = 1; $i <= $pages; $i++) {
        $activeClass = $i == $page ? 'bg-blue-500 text-white' : 'bg-white text-gray-700';
        $table .= '<a href="#" data-page="' . $i . '" class="px-4 py-2 mx-1 border rounded pagination-link ' . $activeClass . '">' . $i . '</a>';
    }
    $table .= '</div>';

    return $table;
}

echo json_encode(['success' => true, 'table' => generate_orders_table($orders, $network, $page)]);
