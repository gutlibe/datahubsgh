<?php

namespace App\Classes;

use App\Classes\Setting;

class HubnetService
{
    private $apiKey;

    public function __construct()
    {
        $setting = new Setting();
        $this->apiKey = $setting->getSetting('hubnet_key');
    }

    public function sendData($order)
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'Hubnet API key is not configured.'
            ];
        }

        $endpoint = 'https://console.hubnet.app/live/api/context/business/transaction/' . strtolower($order['network']) . '-new-transaction';

        $payload = [
            "phone"     => $order['msisdn'],
            "volume"    => (string)$order['volume'],
            "reference" => $order['reference'],
            "webhook"   => rtrim($_ENV['APP_URL'], '/') . '/api/webhook/hubnet/'
        ];

        // Optional referrer
        if (!empty($order['referrer'])) {
            $payload['referrer'] = $order['referrer'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "token: Bearer " . $this->apiKey,
            "Content-Type: application/json",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode === 200) {
            return [
                'success' => true,
                'message' => 'Order sent to Hubnet for processing.',
                'raw_response' => $responseBody
            ];
        } else {
            return [
                'success' => false,
                'message' => "Hubnet Failed: Code {$httpCode}. " . ($error ?: $responseBody),
                'raw_response' => $responseBody
            ];
        }
    }
}
