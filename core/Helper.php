<?php
/**
 * Global Helper Functions
 */

class Helper {
    private static ?array $config = null;

    public static function init(array $config): void {
        self::$config = $config;
    }

    public static function baseUrl(): string {
        $envUrl = getenv('APP_URL');
        if (!empty($envUrl)) {
            return rtrim($envUrl, '/');
        }

        if (isset(self::$config['app']['url']) && !empty(self::$config['app']['url'])) {
            return rtrim(self::$config['app']['url'], '/');
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');
        $protocol = $isHttps ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
        return $protocol . $host;
    }

    public static function url(string $path = ''): string {
        $base = self::baseUrl();
        $path = ltrim($path, '/');
        return $path ? "{$base}/{$path}" : $base;
    }

    public static function asset(string $path): string {
        return self::url($path);
    }

    public static function sanitize(mixed $data): mixed {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return is_string($data) ? htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8') : $data;
    }

    public static function formatCurrency(float|int $amount, string $symbol = '₹'): string {
        return $symbol . number_format($amount, 2);
    }

    public static function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'business-' . time() : $text;
    }

    public static function flash(string $key, ?string $message = null): ?string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }

        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        return null;
    }

    public static function jsonResponse(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public static function calcTier(int $visits, float $spend = 0.0): array {
        if ($visits >= 12 || $spend >= 3000) {
            return [
                'tier' => 'gold',
                'name' => 'Gold VIP',
                'badge' => '🥇',
                'multiplier' => 1.5,
                'perk' => '1.5x Coins on all visits'
            ];
        } elseif ($visits >= 5 || $spend >= 1000) {
            return [
                'tier' => 'silver',
                'name' => 'Silver Regular',
                'badge' => '🥈',
                'multiplier' => 1.2,
                'perk' => '1.2x Coins on all visits'
            ];
        } else {
            return [
                'tier' => 'bronze',
                'name' => 'Bronze Member',
                'badge' => '🥉',
                'multiplier' => 1.0,
                'perk' => '1x Standard Coins'
            ];
        }
    }

    public static function timeAgo(?string $datetime): string {
        if (!$datetime) return 'Never';
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' mins ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
        return date('M d, Y', $timestamp);
    }

    public static function daysSince(?string $datetime): int {
        if (!$datetime) return 999;
        $timestamp = strtotime($datetime);
        return (int) floor((time() - $timestamp) / 86400);
    }
}
