<?php

checkAdmin();

use App\Classes\Configuration;
use App\Classes\ResultChecker;

$configClass = new Configuration();
$rc = new ResultChecker();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $configClass->update('enable_result_checker', isset($_POST['enable_result_checker']) ? '1' : '0');

    $types = $rc->getAllTypes();
    foreach ($types as $type) {
        $key = $type['type_key'];
        $rc->setTypeEnabled($key, isset($_POST['enabled_' . $key]));

        $tiers = [];
        $mins = $_POST['tier_min_' . $key] ?? [];
        $maxs = $_POST['tier_max_' . $key] ?? [];
        $prices = $_POST['tier_price_' . $key] ?? [];

        foreach ($mins as $i => $min) {
            if ($min === '' || !isset($prices[$i]) || $prices[$i] === '') continue;
            $tiers[] = [
                'min_qty' => $min,
                'max_qty' => $maxs[$i] ?? '',
                'unit_price' => $prices[$i]
            ];
        }

        if (!empty($tiers)) {
            $rc->replaceTiers($key, $tiers);
        }
    }

    $_SESSION['success_message'] = 'Result Checker settings updated successfully!';
    redirect(BASE_URL . '/admin/result-checker');
}

$globalEnabled = ($configClass->getAll()['enable_result_checker'] ?? '0') === '1';
$types = $rc->getAllTypes();
foreach ($types as &$type) {
    $type['tiers'] = $rc->getTiers($type['type_key']);
}
unset($type);

view('admin/result-checker', [
    'title' => 'Result Checker',
    'layout' => 'admin',
    'globalEnabled' => $globalEnabled,
    'types' => $types
]);
