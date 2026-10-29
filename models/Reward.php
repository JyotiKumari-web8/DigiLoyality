<?php
/**
 * Reward & Voucher Model
 */

class Reward {
    public static function getByBusiness(int $businessId): array {
        return Database::fetchAll(
            "SELECT * FROM rewards WHERE business_id = ? AND is_active = 1 ORDER BY cost ASC",
            [$businessId]
        );
    }

    public static function create(array $data): int {
        Database::query(
            "INSERT INTO rewards (business_id, title, description, reward_type, cost, discount_value, is_active) 
             VALUES (?, ?, ?, ?, ?, ?, 1)",
            [
                $data['business_id'],
                $data['title'],
                $data['description'] ?? '',
                $data['reward_type'] ?? 'coins',
                $data['cost'] ?? 50,
                $data['discount_value'] ?? 0.00
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function delete(int $id, int $businessId): bool {
        Database::query("DELETE FROM rewards WHERE id = ? AND business_id = ?", [$id, $businessId]);
        return true;
    }

    public static function getCustomerVouchers(int $customerId): array {
        return Database::fetchAll(
            "SELECT * FROM reward_redemptions WHERE customer_id = ? ORDER BY claimed_at DESC",
            [$customerId]
        );
    }

    public static function redeemVoucher(string $voucherCode, int $businessId, ?int $staffId = null): array {
        $redemption = Database::fetch(
            "SELECT * FROM reward_redemptions WHERE voucher_code = ? AND business_id = ?",
            [$voucherCode, $businessId]
        );

        if (!$redemption) {
            throw new Exception("Invalid voucher code.");
        }

        if ($redemption['status'] === 'redeemed') {
            throw new Exception("Voucher has already been redeemed on " . date('M d, Y H:i', strtotime($redemption['redeemed_at'])));
        }

        Database::query(
            "UPDATE reward_redemptions SET status = 'redeemed', redeemed_at = NOW(), staff_id = ? WHERE id = ?",
            [$staffId, $redemption['id']]
        );

        return [
            'success' => true,
            'title' => $redemption['reward_title'],
            'voucher_code' => $voucherCode
        ];
    }
}
