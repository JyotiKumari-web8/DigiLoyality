<?php
/**
 * Staff / Cashier Model
 */

class Staff {
    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM staff WHERE id = ?", [$id]);
    }

    public static function getByBusiness(int $businessId): array {
        return Database::fetchAll(
            "SELECT s.*, 
             (SELECT COUNT(*) FROM visits WHERE staff_id = s.id) as stamps_given
             FROM staff s 
             WHERE s.business_id = ? 
             ORDER BY s.id DESC",
            [$businessId]
        );
    }

    public static function create(int $businessId, string $name, string $email, string $pin, string $role = 'cashier'): int {
        Database::query(
            "INSERT INTO staff (business_id, name, email, pin, role, is_active) VALUES (?, ?, ?, ?, ?, 1)",
            [$businessId, $name, $email, $pin, $role]
        );
        return (int) Database::lastInsertId();
    }

    public static function authenticate(string $email, string $pin): ?array {
        $staff = Database::fetch("SELECT * FROM staff WHERE email = ? AND pin = ? AND is_active = 1", [$email, $pin]);
        if (!$staff) return null;

        $business = Business::findById((int) $staff['business_id']);
        if (!$business || $business['status'] === 'suspended') return null;

        return [
            'staff' => $staff,
            'business' => $business
        ];
    }

    public static function toggleStatus(int $id, int $businessId): bool {
        Database::query(
            "UPDATE staff SET is_active = 1 - is_active WHERE id = ? AND business_id = ?",
            [$id, $businessId]
        );
        return true;
    }
}
