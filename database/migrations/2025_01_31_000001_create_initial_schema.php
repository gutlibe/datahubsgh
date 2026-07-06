<?php

use App\Classes\Migration;

class CreateInitialSchema extends Migration
{
    public function up()
    {
        // 1. Users Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `first_name` varchar(255) NOT NULL,
            `last_name` varchar(255) NOT NULL,
            `phone_number` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `balance` decimal(10,2) DEFAULT 0.00,
            `role` enum('user','admin','customer') DEFAULT 'user',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 2. Products Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `products` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `network` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `volume` int(11) NOT NULL,
            `customer_price` decimal(10,2) NOT NULL,
            `agent_price` decimal(10,2) NOT NULL,
            `validity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `is_available` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `products_network_index` (`network`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 3. Transactions Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `transactions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NULL,
            `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `principal_amount` decimal(10,2) NOT NULL,
            `fees` decimal(10,2) NOT NULL,
            `total_amount` decimal(10,2) NOT NULL,
            `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
            `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `is_credited_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_transaction_user_id` (`user_id`),
            CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 4. Orders Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `orders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `product_id` bigint(20) UNSIGNED DEFAULT NULL,
            `agent_product_id` int(11) DEFAULT NULL,
            `reference` varchar(25) NOT NULL,
            `msisdn` varchar(20) NOT NULL,
            `amount` decimal(10,2) NOT NULL,
            `network` varchar(32) NOT NULL,
            `volume` int(11) NOT NULL,
            `product_name` varchar(255) NOT NULL,
            `status` varchar(32) NOT NULL DEFAULT 'pending',
            `status_message` text DEFAULT NULL,
            `status_updated_at` datetime DEFAULT NULL,
            `mode` varchar(16) DEFAULT NULL,
            `provider` varchar(50) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `source` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_reference_v2` (`reference`),
            KEY `idx_order_user_id_v2` (`user_id`),
            KEY `idx_order_product_id_v2` (`product_id`),
            KEY `idx_order_status_v2` (`status`),
            KEY `idx_order_network_v2` (`network`),
            CONSTRAINT `fk_order_product_v2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
            CONSTRAINT `fk_order_user_v2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 5. Settings Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(255) NOT NULL UNIQUE,
            `setting_value` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 6. Configurations Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `configurations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `value` varchar(255) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`),
            UNIQUE KEY `name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 7. Direct Purchases Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS `direct_purchases` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `transaction_id` int(11) NOT NULL,
            `product_id` bigint(20) UNSIGNED NOT NULL,
            `msisdn` varchar(20) NOT NULL,
            `network` varchar(255) NOT NULL,
            `status` enum('pending','accepted','processing','delivered','failed') NOT NULL DEFAULT 'pending',
            `mode` varchar(255) NOT NULL DEFAULT 'manual',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `transaction_id` (`transaction_id`),
            KEY `product_id` (`product_id`),
            CONSTRAINT `direct_purchases_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
            CONSTRAINT `direct_purchases_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 8. Seed Settings
        $this->db->exec("INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
            ('app_name', 'Data Portal'),
            ('hubnet_key', ''),
            ('paystack_public_key', ''),
            ('paystack_secret_key', ''),
            ('contact_phone', ''),
            ('contact_whatsapp', ''),
            ('email_contact', ''),
            ('telegram_bot_token', ''),
            ('telegram_chat_id', ''),
            ('manual_alert_bot_token', ''),
            ('auto_alert_bot_token', ''),
            ('processing_alert_bot_token', ''),
            ('delivered_alert_bot_token', ''),
            ('failed_alert_bot_token', '')");

        // 9. Seed Configurations
        $this->db->exec("INSERT IGNORE INTO `configurations` (`name`, `value`) VALUES
            ('auto_processing', '1'),
            ('auto_mtn_processing', '1'),
            ('auto_at_processing', '1'),
            ('auto_telecel_processing', '1'),
            ('direct_purchase', '0'),
            ('manual_topup', '0'),
            ('enable_deposit', '1'),
            ('enable_purchase', '1'),
            ('enable_manual_alert', '1'),
            ('enable_auto_alert', '1'),
            ('enable_processing_alert', '1'),
            ('enable_delivered_alert', '1'),
            ('enable_failed_alert', '1'),
            ('enable_direct_purchase', '1'),
            ('mtn_provider', 'hubnet'),
            ('at_provider', 'hubnet'),
            ('telecel_provider', 'hubnet')");
    }

    public function down()
    {
        $this->db->exec("DROP TABLE IF EXISTS `direct_purchases`;");
        $this->db->exec("DROP TABLE IF EXISTS `configurations`;");
        $this->db->exec("DROP TABLE IF EXISTS `settings`;");
        $this->db->exec("DROP TABLE IF EXISTS `orders`;");
        $this->db->exec("DROP TABLE IF EXISTS `transactions`;");
        $this->db->exec("DROP TABLE IF EXISTS `products`;");
        $this->db->exec("DROP TABLE IF EXISTS `users`;");
    }
}