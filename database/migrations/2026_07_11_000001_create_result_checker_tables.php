<?php

use App\Classes\Migration;

class CreateResultCheckerTables extends Migration
{
    public function up()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS `result_checker_types` (
            `type_key` VARCHAR(30) NOT NULL,
            `display_name` VARCHAR(100) NOT NULL,
            `enabled` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`type_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->exec("CREATE TABLE IF NOT EXISTS `result_checker_price_tiers` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `checker_type` VARCHAR(30) NOT NULL,
            `min_qty` INT NOT NULL DEFAULT 1,
            `max_qty` INT NULL,
            `unit_price` DECIMAL(10,2) NOT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `idx_rc_tier_type` (`checker_type`),
            CONSTRAINT `fk_rc_tier_type` FOREIGN KEY (`checker_type`) REFERENCES `result_checker_types` (`type_key`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->exec("CREATE TABLE IF NOT EXISTS `result_checker_orders` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `user_id` INT NULL,
            `checker_type` VARCHAR(30) NOT NULL,
            `display_name` VARCHAR(100) NULL,
            `quantity` INT NOT NULL DEFAULT 1,
            `msisdn` VARCHAR(20) NOT NULL,
            `unit_price` DECIMAL(10,2) NOT NULL,
            `amount` DECIMAL(10,2) NOT NULL,
            `reference` VARCHAR(255) NOT NULL,
            `status` VARCHAR(30) NOT NULL DEFAULT 'pending',
            `cards` TEXT NULL,
            `error_message` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
            `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_rc_order_reference` (`reference`),
            CONSTRAINT `fk_rc_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->exec("INSERT IGNORE INTO `result_checker_types` (`type_key`, `display_name`, `enabled`) VALUES
            ('WASSCE', 'WASSCE Results Checker', 0),
            ('BECE', 'BECE Results Checker', 0)");

        $this->db->exec("INSERT INTO `result_checker_price_tiers` (`checker_type`, `min_qty`, `max_qty`, `unit_price`, `sort_order`) VALUES
            ('WASSCE', 1, NULL, 20.00, 0),
            ('BECE', 1, NULL, 20.00, 0)");

        $this->db->exec("INSERT IGNORE INTO `configurations` (`name`, `value`) VALUES ('enable_result_checker', '0')");
    }

    public function down()
    {
        $this->db->exec("DELETE FROM `configurations` WHERE `name` = 'enable_result_checker'");
        $this->db->exec("DROP TABLE IF EXISTS `result_checker_orders`");
        $this->db->exec("DROP TABLE IF EXISTS `result_checker_price_tiers`");
        $this->db->exec("DROP TABLE IF EXISTS `result_checker_types`");
    }
}
