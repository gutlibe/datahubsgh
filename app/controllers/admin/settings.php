<?php

use App\Classes\Setting;

$setting = new Setting();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsData = [
        'app_name' => $_POST['app_name'] ?? '',
        'hubnet_key' => $_POST['hubnet_key'] ?? '',
        'paystack_public_key' => $_POST['paystack_public_key'] ?? '',
        'paystack_secret_key' => $_POST['paystack_secret_key'] ?? '',
        'contact_phone' => $_POST['contact_phone'] ?? '',
        'contact_whatsapp' => $_POST['contact_whatsapp'] ?? '',
        'email_contact' => $_POST['email_contact'] ?? '',
        'telegram_chat_id' => $_POST['telegram_chat_id'] ?? '',
        'manual_alert_bot_token' => $_POST['manual_alert_bot_token'] ?? '',
        'auto_alert_bot_token' => $_POST['auto_alert_bot_token'] ?? '',
        'processing_alert_bot_token' => $_POST['processing_alert_bot_token'] ?? '',
        'delivered_alert_bot_token' => $_POST['delivered_alert_bot_token'] ?? '',
        'failed_alert_bot_token' => $_POST['failed_alert_bot_token'] ?? '',
    ];

    $all_saved = true;
    foreach ($settingsData as $key => $value) {
        if (!$setting->setSetting($key, $value)) {
            $all_saved = false;
        }
    }

    if ($all_saved) {
        $_SESSION['success_message'] = 'Settings updated successfully!';
    } else {
        $_SESSION['error_message'] = 'Failed to update one or more settings.';
    }
    header('Location: ' . BASE_URL . '/admin/settings');
    exit;
}

$settings = $setting->getAllSettings();

view('admin/settings', [
    'settings' => $settings,
    'layout' => 'admin'
]);
