<?php
/**
 * DigiLoyalty Pro - Front Controller & Router
 */

define('APP_ROOT', dirname(__DIR__));

// Load Core & Models
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Auth.php';
require_once APP_ROOT . '/core/Helper.php';
require_once APP_ROOT . '/models/Business.php';
require_once APP_ROOT . '/models/Customer.php';
require_once APP_ROOT . '/models/Staff.php';
require_once APP_ROOT . '/models/Reward.php';

// Load Controllers
require_once APP_ROOT . '/controllers/CustomerController.php';
require_once APP_ROOT . '/controllers/StaffController.php';
require_once APP_ROOT . '/controllers/OwnerController.php';
require_once APP_ROOT . '/controllers/AdminController.php';
require_once APP_ROOT . '/controllers/InstallController.php';

// Initialize configuration
$configFile = APP_ROOT . '/config/config.php';
$config = file_exists($configFile) ? require $configFile : [];
Database::init($config['db'] ?? []);
Helper::init($config);
Auth::init();

// Parse Request Path
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = str_replace('\\', '/', dirname($scriptName));

// Clean path relative to script directory (only strip non-root subdirectories)
if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '') {
    $scriptDir = rtrim($scriptDir, '/');
    if (strpos($uri, $scriptDir) === 0) {
        $uri = substr($uri, strlen($scriptDir));
    }
}

$path = '/' . trim($uri, '/');
if ($path === '//') {
    $path = '/';
}

// Direct query parameter check for slug (e.g. ?slug=food-bites)
$slugParam = $_GET['slug'] ?? '';

// Check if database needs setup
if (!Database::isConnected() && $path !== '/install') {
    header('Location: ' . Helper::url('install'));
    exit;
}

// Router Logic
if (!empty($slugParam)) {
    // Direct slug parameter matching demo link: business.php?slug=food-bites or index.php?slug=food-bites
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        CustomerController::handleJoin($slugParam);
    } else {
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            CustomerController::logout($slugParam);
        } else {
            CustomerController::view($slugParam);
        }
    }
    exit;
}

switch ($path) {
    case '':
    case '/':
        require APP_ROOT . '/views/landing.php';
        break;

    // Database Installer & Demo Seeder
    case '/install':
        InstallController::index();
        break;

    // Business Owner Authentication & Registration
    case '/login':
        OwnerController::login();
        break;

    case '/register':
        OwnerController::register();
        break;

    case '/owner/dashboard':
        OwnerController::dashboard();
        break;

    case '/owner/card-settings':
        OwnerController::cardSettings();
        break;

    case '/owner/customers':
        OwnerController::customers();
        break;

    case '/owner/staff':
        OwnerController::staff();
        break;

    case '/owner/rewards':
        OwnerController::rewards();
        break;

    case '/owner/standee':
        OwnerController::standee();
        break;

    case '/owner/logout':
        OwnerController::logout();
        break;

    // Staff / Cashier Terminal
    case '/staff/login':
        StaffController::login();
        break;

    case '/staff/terminal':
        StaffController::terminal();
        break;

    case '/staff/logout':
        StaffController::logout();
        break;

    // Reseller / Super Admin Console
    case '/admin/login':
        AdminController::login();
        break;

    case '/admin/dashboard':
        AdminController::dashboard();
        break;

    case '/admin/action':
        AdminController::businessAction();
        break;

    case '/admin/create-business':
        AdminController::createBusiness();
        break;

    case '/admin/login-as-owner':
        $bizId = (int)($_GET['id'] ?? 0);
        AdminController::loginAsOwner($bizId);
        break;

    case '/admin/logout':
        AdminController::logout();
        break;

    // Dynamic Card URL path e.g. /card/food-bites
    default:
        if (preg_match('#^/card/([a-zA-Z0-9_-]+)$#', $path, $matches)) {
            $slug = $matches[1];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                CustomerController::handleJoin($slug);
            } else {
                CustomerController::view($slug);
            }
        } else {
            http_response_code(404);
            require APP_ROOT . '/views/404.php';
        }
        break;
}
