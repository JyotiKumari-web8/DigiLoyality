<?php
/**
 * Role-Based Authentication & Session Management
 */

class Auth {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- Reseller / Admin ---
    public static function loginAdmin(array $admin): void {
        self::init();
        $_SESSION['admin_user'] = [
            'id' => $admin['id'],
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role'] ?? 'reseller',
            'agency_name' => $admin['agency_name'] ?? 'DigiLoyalty Agency'
        ];
    }

    public static function currentAdmin(): ?array {
        self::init();
        return $_SESSION['admin_user'] ?? null;
    }

    public static function checkAdmin(): bool {
        return self::currentAdmin() !== null;
    }

    public static function requireAdmin(): void {
        if (!self::checkAdmin()) {
            Helper::flash('error', 'Please log in as an administrator to access this area.');
            header('Location: ' . Helper::url('admin/login'));
            exit;
        }
    }

    // --- Business Owner ---
    public static function loginOwner(array $business): void {
        self::init();
        $_SESSION['owner_user'] = [
            'business_id' => $business['id'],
            'business_name' => $business['name'],
            'business_slug' => $business['slug'],
            'owner_name' => $business['owner_name'],
            'owner_email' => $business['owner_email'],
            'currency' => $business['currency_symbol'] ?? '₹'
        ];
    }

    public static function currentOwner(): ?array {
        self::init();
        return $_SESSION['owner_user'] ?? null;
    }

    public static function checkOwner(): bool {
        return self::currentOwner() !== null;
    }

    public static function requireOwner(): void {
        if (!self::checkOwner()) {
            Helper::flash('error', 'Please log in to your business dashboard.');
            header('Location: ' . Helper::url('login'));
            exit;
        }
    }

    // --- Staff / Cashier ---
    public static function loginStaff(array $staff, array $business): void {
        self::init();
        $_SESSION['staff_user'] = [
            'id' => $staff['id'],
            'name' => $staff['name'],
            'role' => $staff['role'] ?? 'cashier',
            'business_id' => $business['id'],
            'business_name' => $business['name'],
            'business_slug' => $business['slug']
        ];
    }

    public static function currentStaff(): ?array {
        self::init();
        return $_SESSION['staff_user'] ?? null;
    }

    public static function checkStaff(): bool {
        return self::currentStaff() !== null;
    }

    public static function requireStaff(): void {
        if (!self::checkStaff() && !self::checkOwner()) {
            Helper::flash('error', 'Please log in with staff credentials to open the counter terminal.');
            header('Location: ' . Helper::url('staff/login'));
            exit;
        }
    }

    // --- Customer Session ---
    public static function loginCustomer(array $customer): void {
        self::init();
        $_SESSION['customer_' . $customer['business_id']] = [
            'id' => $customer['id'],
            'mobile' => $customer['mobile'],
            'name' => $customer['name'],
            'business_id' => $customer['business_id'],
            'qr_token' => $customer['qr_token']
        ];
    }

    public static function currentCustomer(int $businessId): ?array {
        self::init();
        return $_SESSION['customer_' . $businessId] ?? null;
    }

    // --- Logout ---
    public static function logout(): void {
        self::init();
        unset($_SESSION['admin_user']);
        unset($_SESSION['owner_user']);
        unset($_SESSION['staff_user']);
    }
}
