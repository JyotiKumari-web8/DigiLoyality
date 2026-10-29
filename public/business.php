<?php
/**
 * Direct Link Entry for Customer Card (matching business.php?slug=food-bites)
 */

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Auth.php';
require_once APP_ROOT . '/core/Helper.php';
require_once APP_ROOT . '/models/Business.php';
require_once APP_ROOT . '/models/Customer.php';
require_once APP_ROOT . '/models/Reward.php';
require_once APP_ROOT . '/controllers/CustomerController.php';

$configFile = APP_ROOT . '/config/config.php';
$config = file_exists($configFile) ? require $configFile : [];
Database::init($config['db'] ?? []);
Helper::init($config);
Auth::init();

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: ' . Helper::url(''));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    CustomerController::handleJoin($slug);
} else {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        CustomerController::logout($slug);
    } else {
        CustomerController::view($slug);
    }
}
