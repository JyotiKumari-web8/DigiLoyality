-- Loyalty Card & Repeat-Visit System Database Schema
-- Compatible with MySQL 5.7+ / 8.0+

CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('superadmin', 'reseller') DEFAULT 'reseller',
    `agency_name` VARCHAR(150) DEFAULT 'DigiLoyalty Agency',
    `agency_phone` VARCHAR(30) DEFAULT '',
    `agency_logo` VARCHAR(255) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `businesses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT DEFAULT 1,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `category` VARCHAR(50) DEFAULT 'Food & Beverage',
    `owner_name` VARCHAR(100) NOT NULL,
    `owner_email` VARCHAR(150) NOT NULL UNIQUE,
    `owner_password` VARCHAR(255) NOT NULL,
    `owner_phone` VARCHAR(30) DEFAULT '',
    `logo_url` VARCHAR(255) DEFAULT '',
    `tagline` VARCHAR(255) DEFAULT 'Earn points & enjoy rewards every visit!',
    `theme_color` VARCHAR(20) DEFAULT '#6366f1',
    `secondary_color` VARCHAR(20) DEFAULT '#ec4899',
    `currency_symbol` VARCHAR(10) DEFAULT '₹',
    `address` TEXT,
    
    -- Stamp Card Configuration
    `stamp_target` INT DEFAULT 8,
    `stamp_reward_title` VARCHAR(150) DEFAULT 'Free Item / ₹150 Off',
    `stamp_icon` VARCHAR(50) DEFAULT 'fa-star',
    
    -- Coin / Spend-based Configuration
    `coin_enabled` TINYINT(1) DEFAULT 1,
    `coin_rate` DECIMAL(10,2) DEFAULT 10.00, -- Spend ₹10 to earn 1 Coin
    `coin_redeem_value` DECIMAL(10,2) DEFAULT 0.50, -- 1 Coin = ₹0.50 discount
    `welcome_bonus_coins` INT DEFAULT 20, -- Bonus coins upon first signup
    `min_redeem_coins` INT DEFAULT 50, -- Minimum coins required to redeem
    
    -- Status & Reseller plan
    `plan_name` VARCHAR(50) DEFAULT 'Standard Growth',
    `plan_expires_at` DATE DEFAULT NULL,
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `staff` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `pin` VARCHAR(10) NOT NULL DEFAULT '1234',
    `role` ENUM('cashier', 'manager') DEFAULT 'cashier',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`business_id`),
    INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `mobile` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `birthday` DATE DEFAULT NULL,
    `qr_token` VARCHAR(64) NOT NULL,
    
    -- Stamps Balance
    `current_stamps` INT DEFAULT 0,
    `total_stamps_earned` INT DEFAULT 0,
    `total_rewards_claimed` INT DEFAULT 0,
    
    -- Coins Balance
    `current_coins` DECIMAL(10,2) DEFAULT 0.00,
    `total_coins_earned` DECIMAL(10,2) DEFAULT 0.00,
    `total_coins_spent` DECIMAL(10,2) DEFAULT 0.00,
    
    -- Visit stats
    `total_visits` INT DEFAULT 0,
    `total_spend` DECIMAL(12,2) DEFAULT 0.00,
    `tier` ENUM('bronze', 'silver', 'gold') DEFAULT 'bronze',
    `last_visit_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `business_customer_mobile` (`business_id`, `mobile`),
    INDEX (`business_id`),
    INDEX (`qr_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `visits` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `staff_id` INT DEFAULT NULL,
    `bill_amount` DECIMAL(10,2) DEFAULT 0.00,
    `stamps_added` INT DEFAULT 1,
    `coins_earned` DECIMAL(10,2) DEFAULT 0.00,
    `reward_completed` TINYINT(1) DEFAULT 0,
    `notes` VARCHAR(255) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`business_id`),
    INDEX (`customer_id`),
    INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `coin_transactions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `staff_id` INT DEFAULT NULL,
    `type` ENUM('credit', 'debit') NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `balance_after` DECIMAL(10,2) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`business_id`),
    INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `rewards` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `reward_type` ENUM('stamps', 'coins') DEFAULT 'coins',
    `cost` INT NOT NULL DEFAULT 50, -- Required stamps or required coins
    `discount_value` DECIMAL(10,2) DEFAULT 0.00,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reward_redemptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `business_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `reward_id` INT DEFAULT NULL,
    `staff_id` INT DEFAULT NULL,
    `reward_title` VARCHAR(150) NOT NULL,
    `voucher_code` VARCHAR(20) NOT NULL UNIQUE,
    `status` ENUM('claimed', 'redeemed', 'expired') DEFAULT 'claimed',
    `claimed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `redeemed_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX (`business_id`),
    INDEX (`customer_id`),
    INDEX (`voucher_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
