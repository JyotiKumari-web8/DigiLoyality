<?php
/**
 * Business / Merchant Model
 */

class Business {
    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM businesses WHERE id = ?", [$id]);
    }

    public static function findBySlug(string $slug): ?array {
        return Database::fetch("SELECT * FROM businesses WHERE slug = ? AND status != 'suspended'", [$slug]);
    }

    public static function findByEmail(string $email): ?array {
        return Database::fetch("SELECT * FROM businesses WHERE owner_email = ?", [$email]);
    }

    public static function getAll(): array {
        return Database::fetchAll("SELECT b.*, 
            (SELECT COUNT(*) FROM customers WHERE business_id = b.id) as total_customers,
            (SELECT COUNT(*) FROM visits WHERE business_id = b.id) as total_visits
            FROM businesses b ORDER BY b.id DESC");
    }

    public static function create(array $data): int {
        $sql = "INSERT INTO businesses (
            admin_id, name, slug, category, owner_name, owner_email, owner_password, owner_phone,
            logo_url, tagline, theme_color, secondary_color, currency_symbol, address,
            stamp_target, stamp_reward_title, coin_enabled, coin_rate, coin_redeem_value,
            welcome_bonus_coins, min_redeem_coins, plan_name, plan_expires_at, status
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?
        )";

        Database::query($sql, [
            $data['admin_id'] ?? 1,
            $data['name'],
            $data['slug'],
            $data['category'] ?? 'General',
            $data['owner_name'],
            $data['owner_email'],
            password_hash($data['owner_password'], PASSWORD_DEFAULT),
            $data['owner_phone'] ?? '',
            $data['logo_url'] ?? '',
            $data['tagline'] ?? 'Earn points & enjoy rewards every visit!',
            $data['theme_color'] ?? '#6366f1',
            $data['secondary_color'] ?? '#ec4899',
            $data['currency_symbol'] ?? '₹',
            $data['address'] ?? '',
            $data['stamp_target'] ?? 8,
            $data['stamp_reward_title'] ?? 'Free Item / Voucher',
            $data['coin_enabled'] ?? 1,
            $data['coin_rate'] ?? 10.00,
            $data['coin_redeem_value'] ?? 0.50,
            $data['welcome_bonus_coins'] ?? 20,
            $data['min_redeem_coins'] ?? 50,
            $data['plan_name'] ?? 'Pro Growth',
            $data['plan_expires_at'] ?? date('Y-m-d', strtotime('+365 days')),
            $data['status'] ?? 'active'
        ]);

        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $fields = [];
        $params = [];

        foreach ($data as $key => $val) {
            $fields[] = "`{$key}` = ?";
            $params[] = $val;
        }

        $params[] = $id;
        $sql = "UPDATE businesses SET " . implode(', ', $fields) . " WHERE id = ?";
        Database::query($sql, $params);
        return true;
    }

    public static function getStats(int $businessId): array {
        $customersCount = (int) (Database::fetch(
            "SELECT COUNT(*) as cnt FROM customers WHERE business_id = ?",
            [$businessId]
        )['cnt'] ?? 0);

        $visitsCount = (int) (Database::fetch(
            "SELECT COUNT(*) as cnt FROM visits WHERE business_id = ?",
            [$businessId]
        )['cnt'] ?? 0);

        $stampsSum = (int) (Database::fetch(
            "SELECT SUM(stamps_added) as sum_stamps FROM visits WHERE business_id = ?",
            [$businessId]
        )['sum_stamps'] ?? 0);

        $coinsSum = (float) (Database::fetch(
            "SELECT SUM(current_coins) as sum_coins FROM customers WHERE business_id = ?",
            [$businessId]
        )['sum_coins'] ?? 0.0);

        // Repeat customers: visited > 1 time
        $repeatCustomers = (int) (Database::fetch(
            "SELECT COUNT(*) as cnt FROM customers WHERE business_id = ? AND total_visits > 1",
            [$businessId]
        )['cnt'] ?? 0);

        $repeatRate = $customersCount > 0 ? round(($repeatCustomers / $customersCount) * 100, 1) : 0;

        // At-risk customers: not visited in 30 days
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $atRiskCount = (int) (Database::fetch(
            "SELECT COUNT(*) as cnt FROM customers WHERE business_id = ? AND last_visit_at < ? AND last_visit_at IS NOT NULL",
            [$businessId, $thirtyDaysAgo]
        )['cnt'] ?? 0);

        // Recent 7 days visits trend
        $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $recentVisits = Database::fetchAll(
            "SELECT DATE(created_at) as visit_date, COUNT(*) as visit_count 
             FROM visits 
             WHERE business_id = ? AND created_at >= ?
             GROUP BY DATE(created_at) 
             ORDER BY visit_date ASC",
            [$businessId, $sevenDaysAgo]
        );

        return [
            'total_customers' => $customersCount,
            'total_visits' => $visitsCount,
            'total_stamps' => $stampsSum,
            'active_coins' => $coinsSum,
            'repeat_rate' => $repeatRate,
            'at_risk_count' => $atRiskCount,
            'recent_visits' => $recentVisits
        ];
    }
}
