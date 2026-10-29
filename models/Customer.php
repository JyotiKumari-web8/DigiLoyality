<?php
/**
 * Customer Model
 */

class Customer {
    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM customers WHERE id = ?", [$id]);
    }

    public static function findByMobile(int $businessId, string $mobile): ?array {
        $clean = preg_replace('/[^0-9]/', '', $mobile);
        return Database::fetch(
            "SELECT * FROM customers WHERE business_id = ? AND (mobile = ? OR mobile = ?)",
            [$businessId, $clean, substr($clean, -10)]
        );
    }

    public static function findByQrToken(string $qrToken): ?array {
        return Database::fetch("SELECT * FROM customers WHERE qr_token = ?", [$qrToken]);
    }

    public static function register(int $businessId, string $mobile, string $name, ?string $email = null, int $bonusCoins = 0): array {
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
        $qrToken = bin2hex(random_bytes(16));

        $sql = "INSERT INTO customers (
            business_id, mobile, name, email, qr_token, 
            current_stamps, current_coins, total_coins_earned, total_visits, tier, last_visit_at
        ) VALUES (?, ?, ?, ?, ?, 0, ?, ?, 1, 'bronze', NOW())";

        Database::query($sql, [
            $businessId,
            $cleanMobile,
            $name,
            $email,
            $qrToken,
            $bonusCoins,
            $bonusCoins
        ]);

        $customerId = (int) Database::lastInsertId();

        // If welcome bonus coins were granted, record transaction
        if ($bonusCoins > 0) {
            Database::query(
                "INSERT INTO coin_transactions (business_id, customer_id, type, amount, balance_after, description) 
                 VALUES (?, ?, 'credit', ?, ?, 'Welcome Bonus Coins on Joining')",
                [$businessId, $customerId, $bonusCoins, $bonusCoins]
            );
        }

        // Record first visit
        Database::query(
            "INSERT INTO visits (business_id, customer_id, bill_amount, stamps_added, coins_earned, notes)
             VALUES (?, ?, 0, 0, ?, 'Account Created & 1st Check-in')",
            [$businessId, $customerId, $bonusCoins]
        );

        return self::findById($customerId);
    }

    public static function addStampAndCoins(
        int $customerId,
        int $stampsToAdd = 1,
        float $coinsToAdd = 0.0,
        float $billAmount = 0.0,
        ?int $staffId = null,
        string $notes = ''
    ): array {
        $customer = self::findById($customerId);
        if (!$customer) {
            throw new Exception("Customer not found.");
        }

        $business = Business::findById((int) $customer['business_id']);
        $stampTarget = (int) ($business['stamp_target'] ?? 8);

        $newStamps = (int) $customer['current_stamps'] + $stampsToAdd;
        $totalStampsEarned = (int) $customer['total_stamps_earned'] + $stampsToAdd;
        $rewardCompleted = 0;

        // Check if stamp card completed
        if ($newStamps >= $stampTarget) {
            $rewardCompleted = 1;
            $newStamps = $newStamps - $stampTarget; // Reset cycle or keep excess
        }

        // Tier multiplier calculation
        $tierInfo = Helper::calcTier((int) $customer['total_visits'] + 1, (float) $customer['total_spend'] + $billAmount);
        $adjustedCoins = $coinsToAdd * $tierInfo['multiplier'];

        $newCoins = (float) $customer['current_coins'] + $adjustedCoins;
        $totalCoinsEarned = (float) $customer['total_coins_earned'] + $adjustedCoins;
        $totalVisits = (int) $customer['total_visits'] + 1;
        $totalSpend = (float) $customer['total_spend'] + $billAmount;

        // Update customer record
        Database::query(
            "UPDATE customers SET 
                current_stamps = ?, 
                total_stamps_earned = ?, 
                current_coins = ?, 
                total_coins_earned = ?, 
                total_visits = ?, 
                total_spend = ?, 
                tier = ?, 
                last_visit_at = NOW() 
             WHERE id = ?",
            [
                $newStamps,
                $totalStampsEarned,
                $newCoins,
                $totalCoinsEarned,
                $totalVisits,
                $totalSpend,
                $tierInfo['tier'],
                $customerId
            ]
        );

        // Record Visit
        Database::query(
            "INSERT INTO visits (business_id, customer_id, staff_id, bill_amount, stamps_added, coins_earned, reward_completed, notes) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $customer['business_id'],
                $customerId,
                $staffId,
                $billAmount,
                $stampsToAdd,
                $adjustedCoins,
                $rewardCompleted,
                $notes
            ]
        );

        // Record Coin Transaction if coins awarded
        if ($adjustedCoins > 0) {
            Database::query(
                "INSERT INTO coin_transactions (business_id, customer_id, staff_id, type, amount, balance_after, description) 
                 VALUES (?, ?, ?, 'credit', ?, ?, ?)",
                [
                    $customer['business_id'],
                    $customerId,
                    $staffId,
                    $adjustedCoins,
                    $newCoins,
                    "Earned from Bill: " . Helper::formatCurrency($billAmount, $business['currency_symbol'] ?? '₹') . " ({$tierInfo['name']})"
                ]
            );
        }

        // If stamp target reached, auto-generate voucher
        $voucherCode = null;
        if ($rewardCompleted) {
            $voucherCode = 'RW-' . strtoupper(substr(md5(uniqid()), 0, 6));
            Database::query(
                "INSERT INTO reward_redemptions (business_id, customer_id, reward_title, voucher_code, status) 
                 VALUES (?, ?, ?, ?, 'claimed')",
                [
                    $customer['business_id'],
                    $customerId,
                    $business['stamp_reward_title'] ?? 'Free Item Reward',
                    $voucherCode
                ]
            );
        }

        return [
            'customer' => self::findById($customerId),
            'reward_completed' => (bool) $rewardCompleted,
            'voucher_code' => $voucherCode,
            'coins_added' => $adjustedCoins,
            'stamps_added' => $stampsToAdd,
            'tier' => $tierInfo
        ];
    }

    public static function redeemCoins(int $customerId, float $coinsToBurn, string $reason, ?int $staffId = null): array {
        $customer = self::findById($customerId);
        if (!$customer) {
            throw new Exception("Customer not found.");
        }

        if ((float) $customer['current_coins'] < $coinsToBurn) {
            throw new Exception("Insufficient coin balance. Customer has {$customer['current_coins']} coins.");
        }

        $newCoins = (float) $customer['current_coins'] - $coinsToBurn;
        $totalCoinsSpent = (float) $customer['total_coins_spent'] + $coinsToBurn;

        Database::query(
            "UPDATE customers SET current_coins = ?, total_coins_spent = ? WHERE id = ?",
            [$newCoins, $totalCoinsSpent, $customerId]
        );

        Database::query(
            "INSERT INTO coin_transactions (business_id, customer_id, staff_id, type, amount, balance_after, description) 
             VALUES (?, ?, ?, 'debit', ?, ?, ?)",
            [$customer['business_id'], $customerId, $staffId, $coinsToBurn, $newCoins, $reason]
        );

        return [
            'new_balance' => $newCoins,
            'coins_burned' => $coinsToBurn
        ];
    }

    public static function getByBusiness(int $businessId, string $filter = 'all'): array {
        $sql = "SELECT * FROM customers WHERE business_id = ?";
        $params = [$businessId];

        if ($filter === 'at_risk') {
            $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
            $sql .= " AND (last_visit_at < ? OR last_visit_at IS NULL)";
            $params[] = $thirtyDaysAgo;
        } elseif ($filter === 'vip') {
            $sql .= " AND total_visits >= 5";
        } elseif ($filter === 'active') {
            $fourteenDaysAgo = date('Y-m-d H:i:s', strtotime('-14 days'));
            $sql .= " AND last_visit_at >= ?";
            $params[] = $fourteenDaysAgo;
        }

        $sql .= " ORDER BY last_visit_at DESC, id DESC";
        return Database::fetchAll($sql, $params);
    }

    public static function getRecentHistory(int $customerId, int $limit = 10): array {
        return Database::fetchAll(
            "SELECT 'visit' as log_type, created_at, bill_amount, stamps_added, coins_earned, notes as description 
             FROM visits WHERE customer_id = ?
             UNION ALL
             SELECT 'coin' as log_type, created_at, 0 as bill_amount, 0 as stamps_added, 
                    CASE WHEN type = 'credit' THEN amount ELSE -amount END as coins_earned, 
                    description 
             FROM coin_transactions WHERE customer_id = ?
             ORDER BY created_at DESC LIMIT ?",
            [$customerId, $customerId, $limit]
        );
    }
}
