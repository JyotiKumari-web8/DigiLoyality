<?php
/**
 * Database Auto-Installer & Demo Seeder Controller
 */

class InstallController {
    public static function index(): void {
        $error = null;
        $success = null;
        $configFile = APP_ROOT . '/config/config.php';
        $currentConfig = file_exists($configFile) ? require $configFile : [];

        $dbHost = $_POST['db_host'] ?? ($currentConfig['db']['host'] ?? '127.0.0.1');
        $dbPort = (int) ($_POST['db_port'] ?? ($currentConfig['db']['port'] ?? 3306));
        $dbName = $_POST['db_name'] ?? ($currentConfig['db']['dbname'] ?? 'loyalty_rewards_db');
        $dbUser = $_POST['db_user'] ?? ($currentConfig['db']['username'] ?? 'root');
        $dbPass = $_POST['db_pass'] ?? ($currentConfig['db']['password'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1. Test server connection
                $pdoServer = new PDO("mysql:host={$dbHost};port={$dbPort};charset=utf8mb4", $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // 2. Create database if not exists
                $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // 3. Connect to database
                $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // 4. Run schema.sql
                $schemaFile = APP_ROOT . '/config/schema.sql';
                if (!file_exists($schemaFile)) {
                    throw new Exception("schema.sql file not found.");
                }

                $sql = file_get_contents($schemaFile);
                $pdo->exec($sql);

                // 5. Save updated config.php
                $configCode = "<?php\n" .
                    "if (!defined('APP_ROOT')) define('APP_ROOT', dirname(__DIR__));\n" .
                    "if (session_status() === PHP_SESSION_NONE) session_start();\n\n" .
                    "return [\n" .
                    "    'app' => [\n" .
                    "        'name' => 'DigiLoyalty Pro',\n" .
                    "        'tagline' => 'Digital Loyalty & Repeat-Visit Engine',\n" .
                    "        'url' => 'http://localhost:8000',\n" .
                    "        'currency_default' => '₹',\n" .
                    "        'version' => '2.0.0',\n" .
                    "    ],\n" .
                    "    'db' => [\n" .
                    "        'host' => " . var_export($dbHost, true) . ",\n" .
                    "        'port' => {$dbPort},\n" .
                    "        'dbname' => " . var_export($dbName, true) . ",\n" .
                    "        'username' => " . var_export($dbUser, true) . ",\n" .
                    "        'password' => " . var_export($dbPass, true) . ",\n" .
                    "        'charset' => 'utf8mb4'\n" .
                    "    ],\n" .
                    "    'reseller' => [\n" .
                    "        'agency_name' => 'DigiLoyalty Reseller Network',\n" .
                    "        'support_email' => 'support@digiloyalty.local',\n" .
                    "        'whatsapp_support' => '+919876543210',\n" .
                    "    ]\n" .
                    "];\n";
                file_put_contents($configFile, $configCode);

                // 6. Initialize Database connection in memory
                Database::init([
                    'host' => $dbHost,
                    'port' => $dbPort,
                    'dbname' => $dbName,
                    'username' => $dbUser,
                    'password' => $dbPass,
                    'charset' => 'utf8mb4'
                ]);

                // Clean installation: No fake or dummy data is seeded
                $success = "Database successfully connected and tables created cleanly! Redirecting to setup your business...";
                header("Refresh: 2; url=" . Helper::url('register'));
            } catch (Exception $e) {
                $error = "Database Error: " . $e->getMessage();
            }
        }

        require APP_ROOT . '/views/install.php';
    }
}

