<?php

namespace App\Classes;

use PDO;
use App\Classes\HubnetService;
use App\Classes\CKGodswayService;

class ProcessingService
{
    private $conn;
    private $order;
    private $user;
    private $product;
    private $configuration;
    private $setting;
    private $telegramNotifier;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->order = new Order();
        $this->user = new User();
        $this->product = new Product();
        $this->configuration = new Configuration();
        $this->setting = new Setting();
        $this->telegramNotifier = new TelegramNotifier();
    }

    public function handleOrderProcessing(int $orderId): void
    {
        $order = $this->order->findOrderByIdForProcessing($orderId);
        if (!$order) {
            return;
        }

        $configs = $this->configuration->getAllAsArray();
        $isAutoProcessingEnabled = (bool)($configs['auto_processing'] ?? false);
        $network = strtolower($order['network']);
        $isNetworkAuto = (bool)($configs["auto_{$network}_processing"] ?? false);

        $mode = ($isAutoProcessingEnabled && $isNetworkAuto) ? 'auto' : 'manual';

        $this->order->updateOrder($orderId, ['mode' => $mode]);

        // Prepare notification data
        $notificationData = [
            'email' => $order['user_id'] ? $this->user->findById($order['user_id'])['email'] : 'Guest Order',
            'network' => $order['network'],
            'reference' => $order['reference'],
            'volume' => $order['volume'],
            'receiver' => $order['msisdn'],
            'source' => $order['source'] === 'direct' ? 'Direct Purchase' : 'User Order',
            'amount' => $order['amount']
        ];
        
        // Add referrer for Hubnet if available
        if ($order['user_id']) {
            $user = $this->user->findById($order['user_id']);
            if ($user && !empty($user['phone_number'])) {
                $order['referrer'] = $user['phone_number'];
            }
        }

        if ($mode === 'auto') {
            // 1. Determine Provider
            $providerSlug = $configs["{$network}_provider"] ?? 'hubnet'; // Default to hubnet
            
            // Tag the order with the provider
            $this->order->updateOrder($orderId, ['provider' => $providerSlug]);

            $result = ['success' => false, 'message' => 'Provider configuration error'];

            // 2. Switch Logic
            if ($providerSlug === 'hubnet') {
                $service = new HubnetService();
                $result = $service->sendData($order);
            } elseif ($providerSlug === 'ckgodsway') {
                $service = new CKGodswayService();
                $result = $service->sendData($order);
            } else {
                // Fallback or Unknown
                $result['message'] = "Unknown provider: $providerSlug";
            }

            // 3. Handle Result
            if ($result['success']) {
                // Use a generic message for the user/database, logging the specific provider details if needed internally
                $this->order->updateStatus($orderId, 'processing', 'Order sent for processing.');
                $this->telegramNotifier->sendAcceptedNotification($notificationData);
            } else {
                $this->order->updateOrder($orderId, [
                    'status' => 'accepted',
                    'status_message' => $result['message'], // Error messages might need details for debugging, but consider sanitizing this too if strictly public
                    'mode' => 'manual'
                ]);
                $this->telegramNotifier->sendManualNotification($notificationData);
            }
        } else {
            // Manual Mode
            $this->order->updateStatus($orderId, 'accepted', 'Order accepted, manual processing required.');
            $this->telegramNotifier->sendManualNotification($notificationData);
        }
    }
}
