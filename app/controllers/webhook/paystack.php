<?php

use App\Classes\Database;
use App\Classes\Setting;
use App\Classes\ProcessingService;
use App\Classes\Order;

// Log the raw input to a file for debugging
$raw_input = file_get_contents('php://input');

$setting = new Setting();
$paystack_secret_key = $setting->getSetting('paystack_secret_key');

// Verify the webhook signature
if (!isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) || $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $raw_input, $paystack_secret_key)) {
    http_response_code(401);
    // Log signature mismatch for debugging
    exit();
}

// Parse the request body
$event = json_decode($raw_input);

// Handle the event
if ($event->event === 'charge.success') {
    $reference = $event->data->reference;

    $pdo = Database::getInstance()->getConnection();

    try {
        $pdo->beginTransaction();

        // Get the transaction
        $stmt = $pdo->prepare('SELECT * FROM transactions WHERE reference = :reference AND is_credited_at IS NULL FOR UPDATE');
        $stmt->bindValue(':reference', $reference);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            // Update the transaction
            $stmt = $pdo->prepare('UPDATE transactions SET status = :status, is_credited_at = NOW() WHERE id = :id');
            $stmt->bindValue(':status', 'success');
            $stmt->bindValue(':id', $transaction['id']);
            $stmt->execute();

            if ($transaction['source'] === 'direct') {
                // Work with unified orders table
                $orderClass = new Order();
                $order = $orderClass->findOrderByReference($transaction['reference']);

                if ($order) {
                    // Update status to 'accepted'
                    $orderClass->updateStatus($order['id'], 'accepted', 'Payment confirmed, processing order.');

                    // Use the ProcessingService to handle the order (passing order ID instead of direct purchase ID)
                    $processingService = new ProcessingService();
                    $processingService->handleOrderProcessing($order['id']);
                }
            } else {
                // Handle user deposit
                $stmt = $pdo->prepare('UPDATE users SET balance = balance + :amount WHERE id = :id');
                $stmt->bindValue(':amount', $transaction['principal_amount']);
                $stmt->bindValue(':id', $transaction['user_id']);
                $stmt->execute();
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        // Log the error
        error_log('Paystack webhook error: ' . $e->getMessage());
    }
}

http_response_code(200);
