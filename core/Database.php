<?php
/**
 * Database Connection & Query Helper
 */

class Database {
    private static ?PDO $instance = null;
    private static ?array $config = null;

    public static function init(array $config): void {
        self::$config = $config;
    }

    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            if (self::$config === null) {
                $configFile = dirname(__DIR__) . '/config/config.php';
                if (file_exists($configFile)) {
                    $appConfig = require $configFile;
                    self::$config = $appConfig['db'] ?? null;
                }
            }

            if (!self::$config) {
                return null;
            }

            $driver = self::$config['driver'] ?? 'sqlite';

            try {
                if ($driver === 'sqlite') {
                    $dbPath = self::$config['database_path'] ?? (dirname(__DIR__) . '/config/database.sqlite');
                    $isNew = !file_exists($dbPath) || filesize($dbPath) === 0;
                    
                    self::$instance = new PDO("sqlite:" . $dbPath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    
                    self::$instance->exec("PRAGMA journal_mode = WAL;");
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                    
                    // Register custom functions for MySQL compatibility
                    self::$instance->sqliteCreateFunction('NOW', function() {
                        return date('Y-m-d H:i:s');
                    });
                    self::$instance->sqliteCreateFunction('CURDATE', function() {
                        return date('Y-m-d');
                    });
                    self::$instance->sqliteCreateFunction('CURRENT_DATE', function() {
                        return date('Y-m-d');
                    });
                    
                    // Auto initialize schema if new database
                    if ($isNew) {
                        $schemaFile = dirname(__DIR__) . '/config/schema_sqlite.sql';
                        if (file_exists($schemaFile)) {
                            self::$instance->exec(file_get_contents($schemaFile));
                        }
                    }
                } else {
                    $host = self::$config['host'] ?? '127.0.0.1';
                    $port = self::$config['port'] ?? 3306;
                    $dbname = self::$config['dbname'] ?? 'loyalty_rewards_db';
                    $user = self::$config['username'] ?? 'root';
                    $pass = self::$config['password'] ?? '';
                    $charset = self::$config['charset'] ?? 'utf8mb4';

                    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
                    self::$instance = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                }
            } catch (PDOException $e) {
                return null;
            }
        }

        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement {
        $pdo = self::getConnection();
        if (!$pdo) {
            throw new Exception("Database not connected. Please run the installer.");
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function lastInsertId(): string {
        $pdo = self::getConnection();
        return $pdo ? $pdo->lastInsertId() : '0';
    }

    public static function isConnected(): bool {
        return self::getConnection() !== null;
    }
}
