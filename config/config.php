<?php
/**
 * Application Configuration
 */

// Prevent direct script access outside of bootstrap
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

return [
    'app' => [
        'name' => 'DigiLoyalty Pro',
        'tagline' => 'Digital Loyalty & Repeat-Visit Engine',
        'url' => getenv('APP_URL') ?: '',
        'currency_default' => '₹',
        'version' => '2.0.0',
    ],
    'db' => [
        'driver' => getenv('DB_DRIVER') ?: 'sqlite',
        'database_path' => dirname(__DIR__) . '/config/database.sqlite',
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'dbname' => getenv('DB_NAME') ?: 'loyalty_rewards_db',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
    ],
    'reseller' => [
        'agency_name' => 'DigiLoyalty Reseller Network',
        'support_email' => 'support@digiloyalty.local',
        'whatsapp_support' => '+919876543210',
    ]
];
