<?php

use App\Classes\Configuration;
use App\Classes\ResultChecker;
use App\Classes\Setting;

$config = (new Configuration())->getAll();
$isEnabled = ($config['enable_result_checker'] ?? '0') === '1';

$rc = new ResultChecker();
$types = [];
if ($isEnabled) {
    foreach ($rc->getEnabledTypes() as $type) {
        $types[] = [
            'checker_type' => $type['type_key'],
            'name' => $type['display_name'],
            'tiers' => $rc->getTiers($type['type_key'])
        ];
    }
}

$setting = new Setting();
$appName = $setting->getSetting('app_name') ?? 'Data Portal';

view('public/result-checker', [
    'title' => $appName . ' - Result Checker',
    'layout' => 'guest',
    'isEnabled' => $isEnabled,
    'types' => $types
]);
