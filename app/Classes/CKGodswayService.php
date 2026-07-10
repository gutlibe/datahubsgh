<?php

namespace App\Classes;

use App\Classes\Setting;

class CKGodswayService
{
    private $apiKey;
    private $baseUrl = 'https://console.ckgodsway.com/api';

    public function __construct()
    {
        $setting = new Setting();
        $this->apiKey = $setting->getSetting('ckgodsway_key');
    }

    /**
     * Verifies that a recipient number is reachable/eligible on the network before
     * a purchase is attempted. Only supported by the CKGodsway API.
     */
    public function verifyNumber($network, $msisdn)
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'CKGodsway API key is not configured.'
            ];
        }

        $networkKey = $this->getNetworkKey($network);
        if (!$networkKey) {
            return [
                'success' => false,
                'message' => "Unsupported network for CKGodsway: " . $network
            ];
        }

        $payload = [
            'networkKey' => $networkKey,
            'recipient' => $msisdn,
            'is_ported_number' => true
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/verify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $response = json_decode($responseBody, true);

        if ($httpCode === 200 && isset($response['success']) && $response['success'] === true) {
            return [
                'success' => true,
                'raw_response' => $responseBody
            ];
        }

        $errorMessage = $response['error'] ?? $response['message'] ?? $error ?? 'Unknown error';
        return [
            'success' => false,
            'message' => "CKGodsway verification failed: {$errorMessage}",
            'raw_response' => $responseBody
        ];
    }

    public function sendData($order)
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'CKGodsway API key is not configured.'
            ];
        }

        // 1. Volume Conversion (MB to GB)
        // DB stores 1000 for 1GB. CK expects 1 for 1GB.
        $capacity = $order['volume'] / 1000;

        // 2. Network Mapping
        $networkKey = $this->getNetworkKey($order['network']);
        if (!$networkKey) {
            return [
                'success' => false,
                'message' => "Unsupported network for CKGodsway: " . $order['network']
            ];
        }

        // 3. Prepare Payload
        $payload = [
            'networkKey' => $networkKey,
            'recipient' => $order['msisdn'],
            'capacity' => (string)$capacity // API expects string
        ];

        // 4. Send Request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/data-purchase');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $response = json_decode($responseBody, true);

        // 5. Handle Response
        if ($httpCode === 200 && isset($response['success']) && $response['success'] === true) {
            return [
                'success' => true,
                'message' => 'Order placed successfully via CKGodsway. Ref: ' . ($response['data']['reference'] ?? 'N/A'),
                'raw_response' => $responseBody
            ];
        } else {
            $errorMessage = $response['error'] ?? $response['message'] ?? $error ?? 'Unknown error';
            return [
                'success' => false,
                'message' => "CKGodsway Failed: {$errorMessage}",
                'raw_response' => $responseBody
            ];
        }
    }

    /**
     * Purchases one or more result checker cards (WASSCE/BECE) via CKGodsway.
     * Normalizes both the single-card ('checker') and bulk ('checkers')
     * response shapes into one cards[] format.
     */
    public function sendResultChecker($checkerType, $msisdn, $quantity, $reference)
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'CKGodsway API key is not configured.'];
        }

        $payload = [
            'checkerType' => $checkerType,
            'recipient' => $msisdn,
            'quantity' => $quantity,
            'reference' => $reference
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/result-checker-purchase');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => 'Connection failed: ' . $error];
        }

        $response = json_decode($responseBody, true);

        if ($httpCode !== 200 || empty($response['success'])) {
            $errorMessage = $response['error'] ?? ($response['message'] ?? 'Unknown error');
            return ['success' => false, 'message' => "CKGodsway Failed: {$errorMessage}"];
        }

        $cards = [];
        if (isset($response['checker'])) {
            $cards[] = [
                'serial' => $response['checker']['serial'] ?? null,
                'pin' => $response['checker']['pin'] ?? null,
                'code' => $response['checker']['code'] ?? null
            ];
        } elseif (isset($response['checkers']) && is_array($response['checkers'])) {
            foreach ($response['checkers'] as $card) {
                $cards[] = [
                    'serial' => $card['serial'] ?? null,
                    'pin' => $card['pin'] ?? null,
                    'code' => $card['code'] ?? null
                ];
            }
        }

        return [
            'success' => true,
            'message' => $response['message'] ?? 'Result checker purchased successfully',
            'cards' => $cards
        ];
    }

    private function getNetworkKey($dbNetwork)
    {
        switch (strtolower($dbNetwork)) {
            case 'mtn':
                return 'MTN_PRO'; // As requested
            case 'telecel':
                return 'TELECEL';
            case 'at':
                return 'AT_PREMIUM';
            default:
                return null;
        }
    }
}
