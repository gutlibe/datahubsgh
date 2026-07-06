<?php

namespace App\Classes;

class TelegramNotifier
{
    private $settings;
    private $configurations;

    public function __construct()
    {
        $setting = new Setting();
        $this->settings = $setting->getAllSettings();
        
        $configuration = new Configuration();
        $this->configurations = $configuration->getAllAsArray();
    }

    private function sendMessage(string $message, string $botToken): void
    {
        $chatId = $this->settings['telegram_chat_id'] ?? null;
        if (empty($botToken) || empty($chatId)) {
            return;
        }

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ],
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    }

    public function sendAcceptedNotification(array $orderData): void
    {
        if (!($this->configurations['enable_auto_alert'] ?? false)) {
            return;
        }

        $botToken = $this->settings['auto_alert_bot_token'] ?? null;

        $network = ucwords($orderData['network']);
        $message = "✅ Order Processed\n\n";
        $message .= "Ref: {$orderData['reference']}\n";
        $message .= "Network: {$network}\n";
        $message .= "Volume: {$orderData['volume']}\n";
        $message .= "Receiver: {$orderData['receiver']}\n";
        $message .= "Amount: GHS {$orderData['amount']}\n";
        if (isset($orderData['new_balance'])) {
            $message .= "New Balance: GHS {$orderData['new_balance']}\n";
        }
        $message .= "Time: " . date('g:i:s A');

        $this->sendMessage($message, $botToken);
    }

    public function sendProcessingNotification(array $orderData): void
    {
        if (!($this->configurations['enable_processing_alert'] ?? false)) {
            return;
        }
        $botToken = $this->settings['processing_alert_bot_token'] ?? null;

        $network = ucwords($orderData['network']);
        $message = "⚙️ Order Processing\n\n";
        $message .= "Ref: {$orderData['reference']}\n";
        $message .= "Network: {$network}\n";
        $message .= "Volume: {$orderData['volume']}\n";
        $message .= "Receiver: {$orderData['receiver']}\n";
        $message .= "Amount: GHS {$orderData['amount']}\n";
        $message .= "Time: " . date('g:i:s A');

        $this->sendMessage($message, $botToken);
    }

    public function sendDeliveredNotification(array $orderData): void
    {
        if (!($this->configurations['enable_delivered_alert'] ?? false)) {
            return;
        }
        $botToken = $this->settings['delivered_alert_bot_token'] ?? null;

        $network = ucwords($orderData['network']);
        $message = "✅ Order Delivered\n\n";
        $message .= "Ref: {$orderData['reference']}\n";
        $message .= "Network: {$network}\n";
        $message .= "Volume: {$orderData['volume']}\n";
        $message .= "Receiver: {$orderData['receiver']}\n";
        $message .= "Amount: GHS {$orderData['amount']}\n";
        $message .= "Time: " . date('g:i:s A');

        $this->sendMessage($message, $botToken);
    }

    public function sendFailedNotification(array $orderData): void
    {
        if (!($this->configurations['enable_failed_alert'] ?? false)) {
            return;
        }
        $botToken = $this->settings['failed_alert_bot_token'] ?? null;

        $network = ucwords($orderData['network']);
        $message = "❌ Order Failed\n\n";
        $message .= "Ref: {$orderData['reference']}\n";
        $message .= "Network: {$network}\n";
        $message .= "Volume: {$orderData['volume']}\n";
        $message .= "Receiver: {$orderData['receiver']}\n";
        $message .= "Amount: GHS {$orderData['amount']}\n";
        $message .= "Time: " . date('g:i:s A');

        $this->sendMessage($message, $botToken);
    }

    public function sendManualNotification(array $orderData): void
    {
        if (!($this->configurations['enable_manual_alert'] ?? false)) {
            return;
        }

        $botToken = $this->settings['manual_alert_bot_token'] ?? null;

        $network = ucwords($orderData['network']);
        $message = "⚠️ Manual Processing Required\n\n";
        $message .= "Ref: {$orderData['reference']}\n";
        $message .= "Network: {$network}\n";
        $message .= "Volume: {$orderData['volume']}\n";
        $message .= "Receiver: {$orderData['receiver']}\n";
        $message .= "Source: {$orderData['source']}\n";
        $message .= "Amount: GHS {$orderData['amount']}\n";
        $message .= "Time: " . date('g:i:s A');

        $this->sendMessage($message, $botToken);
    }
}
